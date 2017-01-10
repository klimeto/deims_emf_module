# Dynamic Ecological Information System (DEIMS) Environmental Monitoring Facility information modul
The code provides an XML encoding for the DEIMS site (meta)data linked with information about persons, networks, datasets, and data products, based on a complex INSPIRE data model for Environmental Monitoring Facilities.

## How to deploy and configure it
- Place the content of the folder in the following folder: profiles/deims/modules/custom/emf

- Import the views from views folder in order to provide data from datasets and data products

- Enable the view mode "emf" for content types: reseach site, person, organization in section "Custom display settings" on e.g. admin/structure/types/manage/person/display for person content type.

- Place the relevant fields in the Active region and and give them the right formatter (emf or other)

- EMF XMLs will be accessed as node/%nid/emf

## Examples
Zöbelboden LTER IM master site (ICP_IM_AT01): http://bolegweb.geof.unizg.hr/deims/node/8611/emf

## Acknowledgements
The module has been developed by MK18 s.r.o. with the support of the Ecopotential project funded by the European Union.
