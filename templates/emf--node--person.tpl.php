<?php
/**
 * This template does not have a surrounding XML element because it is re-used
 * by other elements.
 */
 
 
$street = $content['field_address']['#object']->field_address['und'][0]['thoroughfare'];
$town = $content['field_address']['#object']->field_address['und'][0]['locality'];
$district = $content['field_address']['#object']->field_address['und'][0]['administrative_area'];
$postalCode = $content['field_address']['#object']->field_address['und'][0]['postal_code'];
$country = $content['field_address']['#object']->field_address['und'][0]['country'];


?>
<ef:responsibleParty>
        <base2:RelatedParty>
			<?php print render($content['field_name']); ?>
			<?php 	if ($content['field_organization']){
						print render($content['field_organization']);
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
                        <gco:CharacterString><?php print('Full address: '. $street . ' ' . $town . ' ' . $district . ' ' . $postalCode . ' ' . $country . '.'); ?></gco:CharacterString>
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
			<base2:role xlink:role="<?php print render($content['field_person_role']); ?>" xlink:href="https://data.lter-europe.net/deims/codeList/<?php print render($content['field_person_role']); ?>"/>
			<?php endif; ?>
        </base2:RelatedParty>
    </ef:responsibleParty>


