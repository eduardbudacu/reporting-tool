echo "Generating turnover per day report"

php console.php generate turnover-per-day

echo "Generating turnover per brand report"

php console.php generate turnover-per-brand

echo "Turnover per day with date filtering"

php console.php generate turnover-per-day --startdate=01-05-2018 --enddate=07-05-2018

echo "Turnover per brand from API source"

php console.php generate turnover-per-brand --datasource=api

echo "Turnover per brand from S3 source"

php console.php generate turnover-per-brand --datasource=s3

echo "Publishing turnover per day report to datastudio"

php console.php generate turnover-per-day --datasource=api --publish=datastudio

echo "Publishing turnover per brand report to datastudio"

php console.php generate turnover-per-brand --datasource=s3 --publish=datastudio