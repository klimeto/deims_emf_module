<?php if(!empty($content['field_dataset_legal'])):
$legalAct = field_get_items('node', $node, 'field_dataset_legal');
?>
		 <?php foreach ($legalAct as $item): ?>
				 <ef:legalBackground>
					<base2:LegislationCitation gml:id="sss">
						<base2:name><?php
											$legalActTextFull = $item['value'];
											$legalActArray = explode(';', $legalActTextFull);
											print $legalActArray[0];
										?></base2:name>
						<base2:date>
							<gmd:CI_Date>
								 <gmd:date><gco:Date><?php print $legalActArray[1];?></gco:Date></gmd:date>
								<gmd:dateType>
									<gmd:CI_DateTypeCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml" codeListValue="<?php print $legalActArray[2];?>"  codeSpace="ISOTC211/19115"><?php print $legalActArray[2];?></gmd:CI_DateTypeCode>
								</gmd:dateType>
							</gmd:CI_Date>
						</base2:date>
						<base2:link/>
						<base2:level/>
					</base2:LegislationCitation>
				 </ef:legalBackground>
		<?php endforeach; ?>	 
<?php endif; ?>