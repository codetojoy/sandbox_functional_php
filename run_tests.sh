docker run -v $(pwd):/app --rm phpunit/phpunit SandboxTest 

docker run -v $(pwd):/app --rm phpunit/phpunit MyWriterTest 

docker run -v $(pwd):/app --rm phpunit/phpunit MyReaderTest 

echo "Ready."
