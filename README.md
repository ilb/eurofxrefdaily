# eurofxref-daily
Example: How to transform xml to pdf using php, fopservlet and XSL-FO template

PDF generation consists of three steps:
- Get source xml. In this example we use "Reference rates of European Central Bank" (http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml). 
- Transform source xml to fo using XSL-FO template eurofxref-daily.xsl and XSLTProcessor
- Transform fo to pdf using fopservlet deployed on tomcat

See working example deployed on openshift:
- Source xml: http://php-bystrobank.rhcloud.com/eurofxref-daily/eurofxref-daily.php?format=xml
- Tranformed to fo: http://php-bystrobank.rhcloud.com/eurofxref-daily/eurofxref-daily.php?format=fo
- Tranformed to pdf: http://php-bystrobank.rhcloud.com/eurofxref-daily/eurofxref-daily.php?format=pdf
