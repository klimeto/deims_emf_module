<ef:responsibleParty>
	<base2:RelatedParty>
		<?php print '<base2:organisationName><gco:CharacterString>' . $label . '</gco:CharacterString></base2:organisationName>';?>
<?php if (!empty ($content['field_url'])): ?>
	<?php print '<base2:contact><base2:Contact>' . render($content['field_url']) . '</base2:Contact></base2:contact>';?>
<?php endif; ?>
	</base2:RelatedParty>
</ef:responsibleParty>
    