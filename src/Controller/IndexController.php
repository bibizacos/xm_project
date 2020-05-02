<?php

namespace App\Controller;

use App\Components\QuandlDataProvider;
use App\Form\InputForm;
use App\Models\Quandl;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class IndexController extends AbstractController
{

    /**
     * @Route("/index/index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function index(Request $request)
    {
        $data = [];
        $column_name = [];
        $openPrices = null;
        $closePrices = null;
        $displayData = false;
        $quandl = new Quandl();
        $form = $this->createForm(InputForm::class);
        $form->handleRequest($request);
        // 1) handle the submit (will only happen on POST)
        if ($form->isSubmitted() && $form->isValid()) {
            $dataProvider = new QuandlDataProvider();
            $formData = $form->getData();
            $quandl = $dataProvider->search($formData['company_symbol'], $formData['start_date'], $formData['end_date']);
            if (!is_null($quandl->getName())) {
                $displayData = true;
//                $reportMailer = new ReportMailer();
                // 2) filter displayed columns
//                $reportMailer->sendEmail($_ENV['SENDER_MAIL_USERNAME'], $formData['email'], $quandl->getName(), $formData['start_date'], $formData['end_date']);
                // 3) filter displayed columns
                $filters = [0 => "Date", 1 => "Open", 2 => "High", 3 => "Low", 4 => "Close", 5 => "Volume"];
                $column_name = $this->filterColumns($quandl->getColumnNames(), $filters);
                $data = $this->filterColumnsData($quandl->getData(), $filters);
                // 4) aggregate chart data
                $chartData = $this->getMonthlyChartData($quandl->getData());
                $openPrices = $chartData['openPrices'];
                $closePrices = $chartData['closePrices'];
            }
        }
        return $this->render('index/index.html.twig', [
            'inputForm' => $form->createView(),
            'submitted' => $form->isSubmitted(),
            'displayData' => $displayData,
            'column_name' => $column_name,
            'data' => $data,
            'company' => $quandl->getName(),
            'openPrices' => $openPrices,
            'closePrices' => $closePrices,
        ]);
    }

    /**
     * @param $rowData
     * @return array
     */
    protected function getMonthlyChartData($rowData)
    {
        $openPrices = null;
        $closePrices = null;
        foreach ($rowData as $colIndex => $rowDatum) {
            $date = strtotime($rowDatum[0]);
            $year = date("Y", $date);
            $month = date("m", $date);
            $openPrice = $rowDatum[2];
            $closePrice = $rowDatum[4];
            $openPrices .= "{x: new Date($year, $month), y: $openPrice},";
            $closePrices .= "{x: new Date($year, $month), y: $closePrice},";
        }
        return [
            'openPrices' => $openPrices,
            'closePrices' => $closePrices
        ];
    }

    /**
     * get columns filtered
     * @param $columns
     * @param $filters
     * @return array
     */
    protected function filterColumns($columns, $filters)
    {
        $allowedColumns = [];
        foreach ($columns as $index => $column) {
            if (in_array($column, $filters)) {
                $allowedColumns[$index] = $column;
            }
        }
        return $allowedColumns;
    }

    /**
     * get column filtered data
     * @param $data
     * @param $filters
     * @return array
     */
    protected function filterColumnsData($data, $filters)
    {
        $rowData = [];
        foreach ($data as $index => $datum) {
            foreach ($datum as $columnKey => $columnData) {
                if (isset($filters[$columnKey])) {
                    $rowData[$index][$columnKey] = $columnData;
                }
            }
        }
        return $rowData;
    }

}
