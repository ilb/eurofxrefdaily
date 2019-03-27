# eurofxref-daily
Example: How to transform xml to pdf using php, fopservlet and XSL-FO template

PDF generation consists of three steps:
- Get source xml. In this example we use "Reference rates of European Central Bank" (http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml). 
- Transform source xml to fo using XSL-FO template eurofxref-daily.xsl and XSLTProcessor
- Transform fo to pdf using fopservlet deployed on tomcat

See working example deployed on openshift:
- Source xml: http://www.demo.ilb.ru/eurofxref-daily/eurofxref-daily.php?format=xml
- Transformed to fo: http://www.demo.ilb.ru/eurofxref-daily/eurofxref-daily.php?format=fo
- Transformed to pdf: http://www.demo.ilb.ru/eurofxref-daily/eurofxref-daily.php?format=pdf
