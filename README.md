# eurofxref-daily
Example: How to transform xml to pdf using php, fopservlet and XSL-FO template

PDF generation consists of three steps:
1. Get source xml. In this example we use "Reference rates of European Central Bank" (http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml). 
2. Transform source xml to fo using XSL-FO template eurofxref-daily.xsl
3. Transform fo to pdf using fopservlet deployed on tomcat

See working example deployed on openshift:
http://php-bystrobank.rhcloud.com/eurofxref-daily/eurofxref-daily.php?format=xml
http://php-bystrobank.rhcloud.com/eurofxref-daily/eurofxref-daily.php?format=fo
http://php-bystrobank.rhcloud.com/eurofxref-daily/eurofxref-daily.php?format=pdf
