if [ -f .env ]; then
    source .env
    echo "Loaded .env file"
else
    echo "No .env file found"
    exit 1
fi

DOCKER_CONTAINER="stagify-mysql"

while true
do
  echo "Start docker in:"
  echo "    - u[p]"
  echo "    - s[top]"
  echo "    - b[ackup]"
  echo "    - r[estore]"
  echo "    - g[enerate]"
  echo "    - e[xit]"
  echo ""
  MODE=""
  read -rp "Mode: " MODE

  if [ "$MODE" = "u" ]; then
      echo "Starting containers"
      docker-compose up -d
  elif [ "$MODE" = "s" ]; then
      echo "Stopping containers"
      docker-compose stop
  elif [ "$MODE" = "b" ]; then
      backupFile="backup_$(date '+%Y%m%d_%H%M%S').bak"
      echo "Backup container \"$DOCKER_CONTAINER\" to \"./backups/$backupFile\""
      docker exec $DOCKER_CONTAINER sh -c "mysqldump -u root -p\"$MYSQL_ROOT_PASSWORD\" \"$MYSQL_DATABASE\" > /tmp/$backupFile"
      docker cp "$DOCKER_CONTAINER:/tmp/$backupFile" "./backups/$backupFile"
  elif [ "$MODE" = "r" ]; then
      backupFile=""
      for file in ./backups/*.bak; do
          if [ -z "$backupFile" ] || [ "$file" -nt "$backupFile" ]; then
            backupFile="${file:10}"
          fi
      done
      if [ -z "$backupFile" ]; then
          echo "No backup file found in \"./backups/\""
      else
        echo "Restore container \"$DOCKER_CONTAINER\" from \"./backups/$backupFile\""
        docker cp "./backups/$backupFile" "$DOCKER_CONTAINER:/tmp/$backupFile"
        docker exec $DOCKER_CONTAINER sh -c "mysql -u root -p\"$MYSQL_ROOT_PASSWORD\" \"$MYSQL_DATABASE\" < /tmp/$backupFile"
      fi
  elif [ "$MODE" = "g" ]; then
    echo "Generate database from PHP entities"
    vendor/bin/doctrine orm:schema-tool:create
  elif [ "$MODE" = "e" ]; then
      echo "Exit"
      exit 0
  else
      echo "Unknown mode \"$MODE\""
  fi
  echo ""
done

read -rp "Press enter to continue..."