<?php
$street = $content['field_address']['#object']->field_address['und'][0]['thoroughfare']; // MANDATORY FIELD WITH LABEL: Address 1
$town = $content['field_address']['#object']->field_address['und'][0]['locality']; // MANDATORY FIELD WITH LABEL: City
$district = $content['field_address']['#object']->field_address['und'][0]['administrative_area']; // MANDATORY FIELD WITH LABEL: Province
$postalCode = $content['field_address']['#object']->field_address['und'][0]['postal_code']; // MANDATORY FIELD WITH LABEL: Postal code
$country = $content['field_address']['#object']->field_address['und'][0]['country']; // OPTIONAL ???? FIELD WITH LABEL: Country
$organizationTitle = $content['field_organization']['#object']->field_organization['und'][0]['entity']->title;
?>
<ef:responsibleParty>
        <base2:RelatedParty>
			<?php print render($content['field_name']); ?>
			<?php 	if ($content['field_organization']){
						print '<base2:organisationName><gco:CharacterString>'.$organizationTitle.'</gco:CharacterString></base2:organisationName>';
					}
					else {
						print '<base2:organisationName><gco:CharacterString>LTER Europe</gco:CharacterString></base2:organisationName>';
					}
			?>
			<?php if (!empty($content['field_person_title'])):?>
            <base2:positionName>
                <gco:CharacterString><?php print render($content['field_person_title']); ?></gco:CharacterString>
            </base2:positionName>
			<?php endif; ?>
            <base2:contact>
                <base2:Contact>
					<?php if (!empty($content['field_address'])):?>
					<?php //print_r ($content['field_address']['#object']->field_address['und'][0]); ?>
					<base2:contactInstructions>
                        <gco:CharacterString><?php print($street . ' ' . $town . ' ' . $district . ' ' . $postalCode . ' ' . $country); ?></gco:CharacterString>
                    </base2:contactInstructions>
					<?php endif; ?>
				
					<base2:electronicMailAddress><?php print render($content['field_email']); ?></base2:electronicMailAddress>
					<?php if (!empty($content['field_fax'])):?>
 					<base2:telephoneFacsimile><?php print render($content['field_fax']); ?></base2:telephoneFacsimile>
                    <?php endif; ?>
					<?php if (!empty($content['field_phone'])):?>
					<base2:telephoneVoice><?php print render($content['field_phone']); ?></base2:telephoneVoice>
                    <?php endif; ?>
					<?php if (!empty($content['field_url'])):?>
                    <?php print render($content['field_url']); ?>
					<?php endif; ?>
                </base2:Contact>
            </base2:contact>
			<?php if (!empty($content['field_person_role'])):?>
			<base2:role xlink:role="<?php print render($content['field_person_role']); ?>" />
			<?php endif; ?>
        </base2:RelatedParty>
</ef:responsibleParty>