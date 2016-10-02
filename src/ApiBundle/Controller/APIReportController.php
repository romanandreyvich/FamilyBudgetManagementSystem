<?php

namespace ApiBundle\Controller;

use ApiBundle\Exception\APIIncorrectParametersException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Exception\APINotFoundException;
use ApiBundle\Traits\APIResponsible;

/**
 * Контроллер принимает запросы связанные с получение отчетов.
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 * @Route("/api")
 */
class APIReportController extends APIController
{
    use APIResponsible;
    const REPORT_BY_DATE = 1;
    const REPORT_BY_COMPARE = 2;

    /**
     *
     * Метод report - позволяет получить данные о транзакциях семьи.
     *
     * @Route("/report", name="api_get_report", options = { "expose" = true })
     * @param Request $request
     *
     * @return Response
     */
    public function getReportsAction(Request $request)
    {
        $elements = null;
        $report = $this->get('app.transactions_report');

        switch ($request->get('report_type', 0)) {
            case self::REPORT_BY_DATE:
                if ($request->get('from') && $request->get('to') && $request->get('family')) {
                    $elements = $report->getStatisticsByDate($request);
                } else {
                    throw new APIIncorrectParametersException();
                }
                break;
            case self::REPORT_BY_COMPARE:
                if ($request->get('family')) {
                    $elements = $report->getComparisonStatistics($request);
                } else {
                    throw new APINotFoundException();
                }
                break;
            default:
                throw new APINotFoundException();
                break;
        }

        return $this->getResponse($elements, $this->get('serializer'));
    }
}
