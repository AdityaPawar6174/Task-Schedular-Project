PHP_PATH=$(which php)
CRON_PATH=$(realpath cron.php)
CRON_JOB="0 * * * * $PHP_PATH $CRON_PATH"

crontab -l 2>/dev/null | grep -v "cron.php" > mycron
echo "$CRON_JOB" >> mycron
crontab mycron
rm mycron

echo "Cron job installed: Runs every hour to send task reminders..."
