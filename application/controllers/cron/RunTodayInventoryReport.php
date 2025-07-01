<?php
/**
 * Copyright 2016 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// namespace Google\AdsApi\Examples\AdManager\v202111\ReportService;

require  '/home/discovered-efs/discovered.tv/public_html/application/third_party/googleadsgoogleads-php-lib/vendor/autoload.php';

use Google\AdsApi\AdManager\AdManagerSession;
use Google\AdsApi\AdManager\AdManagerSessionBuilder;
use Google\AdsApi\AdManager\Util\v202111\ReportDownloader;
use Google\AdsApi\AdManager\v202111\Column;
use Google\AdsApi\AdManager\v202111\DateRangeType;
use Google\AdsApi\AdManager\v202111\Dimension;
use Google\AdsApi\AdManager\v202111\ExportFormat;
use Google\AdsApi\AdManager\v202111\ReportJob;
use Google\AdsApi\AdManager\v202111\ReportQuery;
use Google\AdsApi\AdManager\v202111\ReportQueryAdUnitView;
use Google\AdsApi\AdManager\v202111\ServiceFactory;
use Google\AdsApi\Common\OAuth2TokenBuilder;

/**
 * This example runs a typical daily inventory report and saves it in your
 * system's temp directory. It filters on the network's root ad unit ID. This is
 * only to demonstrate filtering for the purposes of this example, as filtering
 * on the root ad unit is equivalent to not filtering on any ad units.
 */
class RunInventoryReport
{

    public static function runExample(
        ServiceFactory $serviceFactory,
        AdManagerSession $session
    ) {
        $reportService = $serviceFactory->createReportService($session);
		
        // Create report query.
        $reportQuery = new ReportQuery();
		
        $reportQuery->setDimensions(
            [
                Dimension::CUSTOM_CRITERIA,
              
            ]
        );
        $reportQuery->setColumns(
            [
				Column::AD_SERVER_TARGETED_IMPRESSIONS,
                Column::AD_SERVER_IMPRESSIONS,
                Column::AD_EXCHANGE_LINE_ITEM_LEVEL_IMPRESSIONS,
                Column::AD_EXCHANGE_LINE_ITEM_LEVEL_REVENUE,
             
              
				
            ]
        );

        // Set the ad unit view to hierarchical.
        $reportQuery->setAdUnitView(ReportQueryAdUnitView::FLAT);
        // Set the start and end dates or choose a dynamic date range type.
        $reportQuery->setDateRangeType(DateRangeType::TODAY);

        // Create report job and start it.
        $reportJob = new ReportJob();
        $reportJob->setReportQuery($reportQuery);
        $reportJob = $reportService->runReportJob($reportJob);

        // Create report downloader to poll report's status and download when
        // ready.
		// echo '<pre>';print_r($reportJob)	;die;	
        $reportDownloader = new ReportDownloader(
            $reportService,
            $reportJob->getId()
        );
        if ($reportDownloader->waitForReportToFinish()) {
            // Write to system temp directory by default.
            // $filePath = sprintf(
                // '%s.csv.gz',
                // tempnam(sys_get_temp_dir(), 'inventory-report-')
            // );
			$TODAY  	= date('Y-m-d'); 
			$file_name 	= 'inventory-report-'.$TODAY ;	 
			$filePath 	= sprintf(
                '%s.csv.gz','/home/discovered-efs/discovered.tv/public_html/downloads/'.$file_name
            );
			
            printf("Downloading report to %s ...%s", $filePath, PHP_EOL);
            // Download the report.
            $reportDownloader->downloadReport(
                ExportFormat::CSV_DUMP,
                $filePath
            );
            echo $file_name;
        } else {
            echo 0;
        }
    }

    public static function main()
    {	
		
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()
            ->build();
			
					
        $session = (new AdManagerSessionBuilder())->fromFile()
            ->withOAuth2Credential($oAuth2Credential)
            ->build();
		 
        self::runExample(new ServiceFactory(), $session);
		
    }
}

RunInventoryReport::main();
