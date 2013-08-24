<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 * @category Piwik_Plugins
 * @package TreemapVisualization
 */
namespace Piwik\Plugins\TreemapVisualization;

use Piwik\Common;
use Piwik\API\Request;

class API
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Gets report data and converts it into data that can be used with the JavaScript Infovis
     * Toolkit's treemap visualization.
     * 
     * @param string $apiMethod The API module & action to call. The result of this method is converted
     *                          to data usable by the treemap visualization. E.g. 'Actions.getPageUrls'.
     * @param string $column The column to generate metric data for. If more than one column is supplied,
     *                       the first is used and the rest discarded.
     * @return array
     */
    public function getTreemapData($apiMethod, $column)
    {
        $dataTable = Request::processRequest("$apiMethod");

        $columns = explode(',', $column);
        $column = reset($columns);

        $generator = new TreemapDataGenerator($column);
        $generator->setInitialRowOffset(Common::getRequestVar('filter_offset', 0, 'int'));
        return $generator->generate($dataTable);
    }
}