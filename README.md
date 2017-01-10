# Dynamic Ecological Information System (DEIMS) Environmental Monitoring Facility (EMF) information XML encoding module
The code provides an XML encoding for the DEIMS site (meta)data linked with information about persons, networks, datasets, and data products, based on [INSPIRE data model for Environmental Monitoring Facilities](http://inspire.ec.europa.eu/Themes/120/2892).

## How to deploy and configure it
- Place the content of the folder in the following folder: profiles/deims/modules/custom/emf

- Import the views from views folder in order to provide data from datasets and data products

- Enable the view mode "emf" for content types: research site, person, organization in section "Custom display settings" on e.g. admin/structure/types/manage/person/display for person content type.

- Place the relevant fields in the Active region and and give them the right formatter (emf or other)

- EMF XMLs will be accessed as node/%nid/emf

## Examples
[Zöbelboden LTER IM master site (ICP_IM_AT01)](http://bolegweb.geof.unizg.hr/deims/node/8611/emf)

[Lago Maggiore](http://bolegweb.geof.unizg.hr/deims/node/8164/emf)

## Acknowledgements
The module has been developed by [MK18 s.r.o.](http://mk18sro.xyz/) with the support of the [Ecopotential project](http://www.ecopotential-project.eu/) funded by the European Union.
