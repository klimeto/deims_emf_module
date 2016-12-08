<?php
// BASE VARIABLES AND REQUIRES
require 'profiles/deims/modules/custom/emf/lib/ext/ShapeFile.inc.php';
$node = menu_get_object();
$uuid = render($content['field_uuid']);
$geobonBiome = field_get_items('node', $node, 'field_site_geobon_biome');
$siteManager = field_get_items('node', $node, 'field_person_contact');
$siteOwner = field_get_items('node', $node, 'field_contact_site_owner');
$siteFunding = field_get_items('node', $node, 'field_contact_funding_agency');
$siteMetadata = field_get_items('node', $node, 'field_person_metadata_provider');
$parentSiteMetadata = field_get_items('node', $node, 'field_parent_site_name');
$subSiteMetadata = field_get_items('node', $node, 'field_subsite_name');
$siteWebAddress = field_get_items('node', $node, 'field_ilter_network_url');
$deimsURL = $GLOBALS['base_url'];
$ilterNetworkMetadata = field_get_items('node', $node, 'field_ilter_national_network_nam');
$otherNetworkMetadata = field_get_items('node', $node, 'field_networks_term_ref');
//$relatedDataset = field_get_items('node', $node, 'field_collected_datasets_ref');
//CONNECTING VIEW TO USE FIELDS FROM RELATED DATASETS
$relatedDatasetMetadata = strip_tags(views_embed_view('datasets_per_site','ds_md',$node->nid));
$relatedDatasetMetadataArray = json_decode($relatedDatasetMetadata);
//$datasetTitle = $relatedDatasetMetadataArray[0]->node->title;
//$datasetLegalAct = $relatedDatasetMetadataArray[0]->node->field_dataset_legal;
//$datasetUUID = $relatedDatasetMetadataArray[0]->node->field_uuid;
$hasObservation = '';


/** COMMENTED 
<gml:LinearRing><gml:posList><?php $posList1 = explode ("POLYGON ((", render($content['field_geo_bounding_box'])); $posList2 = explode ("))", $posList1[1]); print $posList2[0]; ?></gml:posList></gml:LinearRing>
**/

?>



<ef:EnvironmentalMonitoringFacility <?php print $namespaces; ?> gml:id="<?php print "Facility_" . $uuid; ?>">
	
	<?php //print_r($relatedDatasetMetadata); ?>
	<ef:inspireId>
        <base:Identifier>
			<base:localId><?php print render($content['field_uuid']); ?></base:localId>
            <base:namespace><?php print $deimsURL; ?></base:namespace>
        </base:Identifier>
    </ef:inspireId>
	
	<ef:name><?php print render($content['field_site_sitelong']); ?></ef:name>
	
	<ef:additionalDescription><?php print render($content['field_site_description']); ?></ef:additionalDescription>

	<!-- 	CURRENT VALUES IN THE DEIMS LIST ARE: COASTAL, FRESH WATER LAKES, FRESH WATER RIVERS, MARINE, COASTAL
			INSPIRE CODE LIST VALUES ARE: AIR, BIOTA, LANDSCAPE, SEDIMENT, SOIL/GROUND, WASTE, WATER
	-->
	<?php if (!empty($geobonBiome)):?>
		<?php foreach ($geobonBiome as $item): ?>
			<ef:mediaMonitored xlink:href="<?php print $deimsURL . "/codeList/GeoboneBiome/" . $item[value]; ?>"/>
		<?php endforeach; ?>
	<?php endif; ?>
	
	
	<?php if (!empty($relatedDatasetMetadata)):
			$k = 0;
			//$datasetLegalAct = '';
			//$datasetUUID = '';
			$num_datasets = 0;
			foreach($relatedDatasetMetadataArray AS $key => $value) {
					//print 'KEY: '.$key.'<br>';
					if (count($value) > $num_datasets) {
						$num_datasets = count($value);
					}
				}
				//echo "NUM_ARRAYS: " . $num_arrays;
				for($k = 0; $k < $num_datasets; $k++) {
					foreach($relatedDatasetMetadataArray AS $value) {
						$datasetTitle = $value->node->title;
						$datasetLegalAct = $value->node->field_dataset_legal;
						$datasetUUID = $value->node->field_uuid;
						$datasetURL = $value->node->field_online_locator;
						//print $datasetURL;
						$datasetURLArray = explode(';', $datasetURL);
						//
						$searchword = 'service=SOS';
						$matches = array_filter($datasetURLArray, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var);});
						//print_r($matches);
						//print_r($datasetURLArray);
						$arr = array();
						//$distributionURL = explode('Distribution URL:Â  ', $datasetURLArray);
						foreach ($matches as $key => $arrvalue){
							if (empty($arrvalue)) {
							   unset($matches[$key]);
							}
							$arr[] = array (
								'URL' => extract_unit($arrvalue, 'Distribution URL:Â  ', 'Distribution Function'),
								'FUNCTION' => substr($arrvalue, strpos($arrvalue, "Distribution Function:") + 24),
							);
								$innerarrUrl = extract_unit($arrvalue, 'Distribution URL:Â  ', 'Distribution Function');
								$innerarrFun = substr($arrvalue, strpos($arrvalue, "Distribution Function:") + 24);
								$arr['URL'] = $innerarrUrl;
								//$hasObservation = strpos($innerarrUrl,'service=SOS');
								//$searchword = 'service=SOS';
								//$matches = array_filter($arrvalue, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); });
								//print $matches;
								$arr['FUNCTION'] = $innerarrFun;
								$arrrr = array($arr);
						};
						//print_r($arr);
						if (!empty ($arr)){
						$hasObservation .= '<ef:hasObservation xlink:href="'. $arr[0]['URL'] .'"/>';
						}
						?>
						<?php if (!empty($datasetLegalAct)) : ?>
						<ef:legalBackground>
							<base2:LegislationCitation gml:id="<?php print "Dataset_" . $datasetUUID . "_". uniqid()?>">
								<base2:name>
									<?php
									//$legalActTextFull = $item['value'];
									$legalActSemi = str_replace(',',';',$datasetLegalAct);
									$legalActArray = explode(';', $legalActSemi);
									print $legalActArray[0];
									?>
								</base2:name>
								<base2:date>
									<gmd:CI_Date>
										<gmd:date>
											<gco:Date><?php print $legalActArray[1];?></gco:Date>
										</gmd:date>
										<gmd:dateType>
											<gmd:CI_DateTypeCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml"
																codeListValue="<?php print $legalActArray[2];?>"
																codeSpace="ISOTC211/19115"><?php print $legalActArray[2];?></gmd:CI_DateTypeCode>
										</gmd:dateType>
									</gmd:CI_Date>
								</base2:date>
								<base2:link/>
								<base2:level/>
							</base2:LegislationCitation>
						</ef:legalBackground>
						<?php endif; ?>
						<?php
					}
				//}
				$k++;
				}
				?>
	
	<?php endif; ?>
	
	<?php if (!empty($content['field_person_contact'])):?>
		<?php print render($content['field_person_contact']); ?>
	<?php endif; ?>
	
	<?php if (!empty($content['field_contact_site_owner'])):?>
			<?php print render($content['field_contact_site_owner']); ?>
	<?php endif; ?>
	
	<?php if (!empty($content['field_contact_funding_agency'])):?>
		<?php print render($content['field_contact_funding_agency']); ?>
	<?php endif; ?>
	
	<?php if (!empty($content['field_person_metadata_provider'])):?>
		<?php print render($content['field_person_metadata_provider']); ?>
	<?php endif; ?>
	
	<?php if ((!empty($content['field_geo_bounding_box'])) || (!empty($content['field_coordinates'])) || (!empty($content['field_upload_shapefile']))) :?>
	<ef:geometry>
        <gml:MultiGeometry gml:id="<?php print render($content['field__site_sitecode']) ?>">
			<?php 
			if (!empty($content['field_geo_bounding_box'])): 
				$geoJson = render($content['field_geo_bounding_box']);
				$obj = json_decode($geoJson);
				$array = $obj->coordinates[0];
				//print_r ($obj->coordinates);
				$j = 0;
				//$jsonPosList = '';
				//$num_arrays = -1;
				foreach($array AS $key => $value) {
					//print 'KEY: '.$key.'<br>';
					if (count($value) > $num_arrays) {
						$num_arrays = count($value);
					}
				}
				//echo "NUM_ARRAYS: " . $num_arrays;
				for($j = 0; $j < $num_arrays-1; $j++) {
					foreach($array AS $value) {
						$jsonPosList .= $value[1] .' '.$value[0].' ';
					}
				}
				$j++;
				?>
				<gml:geometryMember>
					<gml:Polygon gml:id="<?php print render($content['field__site_sitecode']) . '_BOUNDS'; ?>" srsName="http://www.opengis.net/def/crs/EPSG/0/4326">
						<gml:exterior>
								<gml:LinearRing><gml:posList><?php print rtrim($jsonPosList,' ') ?></gml:posList></gml:LinearRing>
						</gml:exterior>
					</gml:Polygon>
				</gml:geometryMember>
				<?php endif; ?>
				<?php if (!empty($content['field_coordinates'])):?>
				<gml:geometryMember>
					<gml:Point gml:id="<?php print render($content['field__site_sitecode']) . '_POINT'; ?>" srsName="http://www.opengis.net/def/crs/EPSG/0/4326">
						<?php print render($content['field_coordinates']); ?>
					</gml:Point>
				</gml:geometryMember>
			<?php endif; ?>
			<?php if (!empty($content['field_upload_shapefile'])):?>
			<?php 	$shpZipURL = render($content['field_upload_shapefile']);
					//echo $shpZipURL;
					$shpEmfZipFile = 'profiles/deims/modules/custom/emf/data/shp/'. basename($shpZipURL);
					$shpEmfFile = basename($shpEmfZipFile,'.zip');
					//echo $shpEmfFile;
					$copy = copy($shpZipURL, $shpEmfZipFile); 
					$path = pathinfo( realpath( $shpEmfZipFile ), PATHINFO_DIRNAME );
					$zip = new ZipArchive;
					$res = $zip->open($shpEmfZipFile);
					if ($res === TRUE) {
						$zip->extractTo( $path );
						$zip->close();
					}
					else {
						echo "Doh! I couldn't open $shpEmfZipFile";
					}
					
					
					$options = array('noparts' => false); 
					$shp = new ShapeFile('profiles/deims/modules/custom/emf/data/shp/'. $shpEmfFile . ".shp", $options);
					$i = 0;
					$shpPosList = '';
					while ($record = $shp->getNext()) { 
						//$dbf_data = $record->getDbfData(); 
						$shp_data = $record->getShpData(); 
						//Dump the information 
						//print_r($dbf_data);
						$array = $shp_data['parts'][0]['points'];
						$num_elements = 0;
						//$shpPosList = '';
						foreach($array AS $key => $value) {
							//print 'KEY: '.$key.'<br>';
							if (count($value) > $num_elements) {
								$num_elements = count($value);
								//echo $num_elements;
							}
						}
						for($i = 0; $i < $num_elements-1; $i++) {
							foreach($array AS $value) {
								//print ('VALUE: ' . $value[$i][x]);
								$shpPosList .= $value['y'] .' '.$value['x'].' ';
								//echo $shpPosList;
								//return;
							}
						}
						//print_r($array); 
						$i++; 
					} 
					
					?>
			<gml:geometryMember>
				<gml:Polygon gml:id="<?php print render($content['field__site_sitecode']) . '_SHAPE'; ?>" srsName="http://www.opengis.net/def/crs/EPSG/0/4326">
					<gml:exterior>
							<gml:LinearRing><gml:posList><?php print rtrim($shpPosList,' ') ?></gml:posList></gml:LinearRing>
					</gml:exterior>
				</gml:Polygon>
			</gml:geometryMember>
			<?php endif; ?>
		</gml:MultiGeometry>
	</ef:geometry>
	<?php endif; ?>
	
	<ef:onlineResource><?php print($deimsURL ."/site/" . render($content['field_uuid']))?></ef:onlineResource>
	<?php if (!empty($content['field_ilter_network_url'])):?>
		<?php foreach($siteWebAddress as $item): ?>
			<ef:onlineResource><?php print $item['url']; ?></ef:onlineResource>
		<? endforeach; ?>
	<?php endif; ?>

	<?php if (!empty($content['field_purpose'])):?>
		<!--<ef:purpose><?php print render($content['field_purpose']); ?></ef:purpose>-->
		<ef:purpose xlink:href="http://inspire.ec.europa.eu/codelist/PurposeOfCollectionValue"/>
	<?php endif; ?>
	
	<?php if (!empty($content['field_site_params']) || !empty($content['field_parameters_taxonomy'])):
	$paramEnvthes =  strip_tags(render($content['field_parameters_taxonomy']));
	$paramSite = render($content['field_site_params']);
	$paramSiteArray = explode(' -- ', $paramSite);
	$paramEnvthesArray = explode(', ', $paramEnvthes);?>
	<?php foreach ($paramEnvthesArray as $item): ?>
	<ef:observingCapability>
        <ef:ObservingCapability gml:id="<?php print "ObservingCapability_" . render($content['field__site_sitecode']) . "_" . uniqid(); ?>">
            <ef:observingTime xsi:nil="true" nilReason="Unpopulated"/>
            
			<!-- codeList http://inspire.ec.europa.eu/codeList/ProcessTypeValue/ with values: Process, SensorML -->
			<ef:processType xsi:nil="true" nilReason="Unpopulated"/>
			<!-- codeList http://inspire.ec.europa.eu/codeList/ResultNatureValue/ with values: primary, processed, simulated -->
            <ef:resultNature xsi:nil="true" nilReason="Unpopulated"/>
            <ef:procedure xlink:href="<?php print $deimsURL ."/node/" . $uuid . "/procedure/sensorML" ?>"/>
			<ef:featureOfInterest xlink:href="<?php print $deimsURL ."/node/". $uuid ."/foi/foi_id" ?>"/>
            <ef:observedProperty xlink:href="<?php 
												$sparqlQuery = ("http://vocabs.ceh.ac.uk/evn/tbl/sparql?default-graph-uri=urn:x-evn-pub:envthes&format=text/json&query=SELECT%20%3Fresult%0AWHERE%20%7B%0A%09GRAPH%20%3Curn%3Ax-evn-pub%3Aenvthes%3E%20%7B%0A%09%09%3Fresult%20a%20%3Chttp%3A%2F%2Fwww.w3.org%2F2004%2F02%2Fskos%2Fcore%23Concept%3E%20.%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20FILTER%20EXISTS%20%7B%0A%20%20%20%20%20%20%20%20%20%20%20%3Fresult%20%3FanyProperty%20%3FanyValue%20.%0A%20%20%20%20%20%20%20%20%20%20%20FILTER%20(isLiteral(%3FanyValue)%20%26%26%20regex(LCASE(str(%3FanyValue))%2C%20%22(%3F%3D.*". $item ."*)%22))%20.%0A%20%20%20%20%20%20%20%7D%20.%0A%09%7D%0ABIND%20(%3Chttp%3A%2F%2Fwww.w3.org%2F2004%2F02%2Fskos%2Fcore%23prefLabel%3E(%3Fresult)%20AS%20%3Flabel)%20.%0A%7D%20ORDER%20BY%20(LCASE(%3Flabel))");
												// Get cURL resource
												$curl = curl_init();
												// Set some options - we are passing in a useragent too here
												curl_setopt_array($curl, array(
													CURLOPT_RETURNTRANSFER => 1,
													CURLOPT_URL => $sparqlQuery,
													CURLOPT_USERAGENT => 'Codular Sample cURL Request'
												));
												// Send the request & save response to $resp
												$resp = curl_exec($curl);
												// Close request to clear up some resources
												curl_close($curl);
												print $sparqlQuery;
			?>"/>
        </ef:ObservingCapability>
    </ef:observingCapability>
	<?php endforeach; ?>
	<?php foreach ($paramSiteArray as $item): ?>
	<ef:observingCapability>
        <ef:ObservingCapability gml:id="<?php print "ObservingCapability_" . render($content['field__site_sitecode']) . "_" . uniqid(); ?>">
            <ef:observingTime xsi:nil="true" nilReason="Unpopulated"/>
            
			<!-- codeList http://inspire.ec.europa.eu/codeList/ProcessTypeValue/ with values: Process, SensorML -->
			<ef:processType xsi:nil="true" nilReason="Unpopulated"/>
			<!-- codeList http://inspire.ec.europa.eu/codeList/ResultNatureValue/ with values: primary, processed, simulated -->
            <ef:resultNature xsi:nil="true" nilReason="Unpopulated"/>
            <ef:procedure xlink:href="<?php print $deimsURL ."/node/" . $uuid . "/procedure/sensorML" ?>"/>
			<ef:featureOfInterest xlink:href="<?php print $deimsURL ."/node/". $uuid ."/foi/foi_id" ?>"/>
            <ef:observedProperty xlink:href="<?php print $deimsURL ."/node/". $uuid ."/observedProperty/" . $item;?>"/>
        </ef:ObservingCapability>
    </ef:observingCapability>
	<?php endforeach; ?>
	<?php endif; ?>
	
	<?php if (!empty($content['field_parent_site_name'])):
	$parentSiteNodeId = $parentSiteMetadata[0]['node']->nid;
	$parentSiteNodeUUID = $parentSiteMetadata[0]['node']->uuid;
	?>
	 <ef:broader xlink:href="<?php print($deimsURL . "/node/" . $parentSiteNodeId . "/emf"); ?>">
        <ef:Hierarchy gml:id="<?php print "Facility_" . $parentSiteNodeUUID; ?>">
            <ef:linkingTime>
                <gml:TimePeriod gml:id="timePeriod01">
                    <gml:beginPosition>1960-01-01</gml:beginPosition>
                    <gml:endPosition indeterminatePosition="now"/>
                </gml:TimePeriod>
            </ef:linkingTime>
            <ef:broader/>
            <ef:narrower/>
        </ef:Hierarchy>
    </ef:broader>
	<?php endif; ?>
	
	<?php if (!empty($content['field_subsite_name'])):
		foreach ($subSiteMetadata as $item):
		$subSiteNodeId = $item['node']->nid;
		$subSiteNodeUUID = $item['node']->uuid;
		?>
			 <ef:narrower xlink:href="<?php print($deimsURL . "/node/" . $subSiteNodeId . "/emf"); ?>">
				<ef:Hierarchy gml:id="<?php print "Facility_" . $subSiteNodeUUID; ?>">
					<ef:linkingTime>
						<gml:TimePeriod gml:id="timePeriod_<?php print $subSiteNodeUUID; ?>">
							<gml:beginPosition>1960-01-01</gml:beginPosition>
							<gml:endPosition indeterminatePosition="now"/>
						</gml:TimePeriod>
					</ef:linkingTime>
					<ef:broader/>
					<ef:narrower/>
				</ef:Hierarchy>
			</ef:narrower>
		<?php endforeach; ?>
	<?php endif; ?>
	
	
	<?php 
		$sosService = render ($content['field_site_dataservi']);
	if ($sosService === 'Sensor Web Enablement (SWE)'): ?>
		<?php foreach ($paramSiteArray as $item): ?>
			<ef:hasObservation xlink:href="<?php print $deimsURL . "/sos?REQUEST=GetObservation&SERVICE=SOS&VERSION=1.0.0&OFFERING=".$deimsURL."/sos/observedProperty/offeringID&OBSERVEDPROPERTY=".$deimsURL."/sos/observedProperty/".$item."&RESPONSEFORMAT=text/xml;subtype=&quot;om/1.0.0;" ?>"/>
		<?php endforeach; ?>
		
		
	<?php endif; ?>
	
	<?php print $hasObservation;?>
	
	
	
	
	<?php if (!empty($content['field_coordinates'])):?>
	<ef:representativePoint>
        <gml:Point gml:id="<?php print render($content['field__site_sitecode']) . '_CENTROID'; ?>" srsName="http://www.opengis.net/def/crs/EPSG/0/4326">
			<?php print render($content['field_coordinates']); ?>
        </gml:Point>
    </ef:representativePoint>
	<?php endif; ?>
	
	<ef:measurementRegime xlink:href="http://inspire.ec.europa.eu/codeList/MeasurementRegimeValue/continuousDataCollection"/>
	
	<ef:mobile>false</ef:mobile>
	
	<ef:operationalActivityPeriod xsi:nil="false">
		<ef:OperationalActivityPeriod gml:id="operationalActivityPeriond_<?php print render($content['field_uuid']); ?>">
			<ef:activityTime>
				<gml:TimePeriod gml:id="timePeriod_<?php print render($content['field_uuid']); ?>">
				
					<gml:beginPosition><?php print render($content['field_year']) . "-01-01";?></gml:beginPosition>
					<?php if (!empty($content['field_year_closed'])):?>
					<gml:endPosition><?php print render($content['field_year_closed']) . "-01-01";?></gml:endPosition>
					<?php else: ?>
					<gml:endPosition indeterminatePosition="now"/>
					<?php endif; ?>
				</gml:TimePeriod>
			</ef:activityTime>
		</ef:OperationalActivityPeriod>
    </ef:operationalActivityPeriod>
	
	<?php 	$networkTitle = $ilterNetworkMetadata[0]['entity']->title;
			$networkNid = $ilterNetworkMetadata[0]['entity']->nid;
			$networkUuid = $ilterNetworkMetadata[0]['entity']->uuid;
			$networkCreated = $ilterNetworkMetadata[0]['entity']->created; 
	?>
	
	<ef:belongsTo xlink:href="<?php print $deimsURL . "/node/" . $networkNid ?>">
        <ef:NetworkFacility gml:id="<?php print "Network_" . $networkUuid ?>">
			<gml:name><?php print $networkTitle ?></gml:name>
            <ef:linkingTime>
                <gml:TimePeriod gml:id="timePeriod_<?php print $networkUuid ?>">
                    <gml:beginPosition><?php print date('Y-m-d',$networkCreated) ?></gml:beginPosition>
                    <gml:endPosition indeterminatePosition="now"/>
                </gml:TimePeriod>
            </ef:linkingTime>
            <ef:belongsTo/>
            <ef:contains/>
        </ef:NetworkFacility>
    </ef:belongsTo>
	
	<?php if (!empty($content['field_networks_term_ref'])):?>
		<?php foreach ($otherNetworkMetadata as $item):?>
		<?php //print_r($item['taxonomy_term']); ?>
			<ef:belongsTo>
				<ef:NetworkFacility gml:id="<?php print ("Network_" . $item['taxonomy_term']->uuid) ?>">
					<gml:name><?php print($item['taxonomy_term']->name); ?></gml:name>
					<ef:linkingTime>
						<gml:TimePeriod gml:id="timePeriod_<?php print print ($item['taxonomy_term']->uuid) ?>">
							<gml:beginPosition><?php print date('Y-m-d',$networkCreated) ?></gml:beginPosition>
							<gml:endPosition indeterminatePosition="now"/>
						</gml:TimePeriod>
					</ef:linkingTime>
					<ef:belongsTo/>
					<ef:contains/>
				</ef:NetworkFacility>
			</ef:belongsTo>
			
		<?php endforeach; ?>
	<?php endif; ?>

</ef:EnvironmentalMonitoringFacility>