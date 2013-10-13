import xml.etree.ElementTree as ET
import os

def Read_XML():

 #Open file
 tree = ET.parse('../../../settings.xml.ref')
 root = tree.getroot()

 #Pull dataset titles
 datasets = []
 variables = []
 groups = root.find('variables').findall('group')
 for group in groups:
  group_name = group.attrib['name']
  for variable in group.findall('datatype'):
   variable_title = variable.attrib['title']
   if variable_title not in variables:
    datasets.append(variable_title)
   for dataset in variable.findall('dataset'):
    dataset_title = dataset.attrib['title']
    if dataset_title not in datasets:
     datasets.append(dataset_title)

 return datasets,variables

datasets,variables = Read_XML()

#Write ot temporary php file
fp = open('settings_xml.php','w')
fp.write('<?php\n')
for dataset in datasets:
 fp.write("$_('%s')\n" % dataset)
for variable in variables:
 fp.write("$_('%s')\n" % variable)
fp.write('?>\n')
fp.close()

#2.Recursively create the pot file
os.chdir('../..')
os.system('xgettext --from-code=utf-8 -k_e -k_x -k__ -o languages/templates/awcm.pot $(find . -name "*.php")')
#msgmerge -o ../i18n/fr/awcm_french_v2.po ../i18n/fr/awcm_french.po awcm.pot 
#msgfmt -o file.mo file.po
