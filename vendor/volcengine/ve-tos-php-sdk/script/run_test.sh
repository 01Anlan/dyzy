if [ -d $PWD/script ]; then
  chmod +x $PWD/script/composer.phar
  chmod +x $PWD/script/phpunit.phar
  rm -rf $PWD/temp
  rm -rf $PWD/temp2
  rm $PWD/test/result.txt
  $PWD/script/composer.phar update
  $PWD/script/composer.phar install
  $PWD/script/phpunit.phar $PWD/test/Test --testdox > $PWD/test/result.txt
else
  chmod +x composer.phar
  chmod +x phpunit.phar
  rm -rf ../temp
  rm -rf ../temp2
  rm ../test/result.txt
  ./composer.phar update
  ./composer.phar install --working-dir ../
  ./phpunit.phar ../test/Test --testdox > ../test/result.txt
fi