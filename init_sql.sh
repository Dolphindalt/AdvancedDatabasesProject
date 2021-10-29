# Obviously, this is only for the development env.
echo "DROP DATABASE rmn_auto;" | sudo mysql -u root
sudo mysql -u root < create_database.sql
sudo mysql -u root < create_tables.sql
sudo mysql -u root < views.sql
sudo mysql -u root < stored_procs.sql
sudo mysql -u root < dummy_data.sql