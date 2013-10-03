import xml.etree.ElementTree as ET
import os

def Read_XML():

 #Open file
 tree = ET.parse('../../../settings.xml.ref')
 root = tree.getroot()

 #Pull dataset titles
 datasets = []
 groups = root.find('variables').findall('group')
 for group in groups:
  group_name = group.attrib['name']
  for variable in group.findall('datatype'):
   variable_name = variable.attrib['name']
   variable_units = variable.attrib['units']
   variable_mask = variable.attrib['mask']
   for dataset in variable.findall('dataset'):
    dataset_title = dataset.attrib['title']
    if dataset_title not in datasets:
     datasets.append(dataset_title)

 return datasets

datasets = Read_XML()

#Write ot temporary php file
fp = open('settings_xml.php','w')
fp.write('<?php\n')
for dataset in datasets:
 fp.write("$_('%s')\n" % dataset)
fp.write('?>\n')
fp.close()

#2.Recursively create the pot file
os.chdir('../..')
os.system('xgettext --from-code=utf-8 -k_e -k_x -k__ -o languages/templates/awcm.pot $(find . -name "*.php")')
