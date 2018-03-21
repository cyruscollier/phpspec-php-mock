<?php

namespace PhpSpec\PhpMock\Test {

    class FunctionExampleAbstract
    {
        function getServerTime()
        {
            return microtime(true);
        }
    }
}

namespace PhpSpec\PhpMock {

    use PhpSpec\PhpMock\Test\FunctionExampleAbstract;

    class FunctionExample extends FunctionExampleAbstract
    {
        function getCurrentTime()
        {
            return time();
        }

        function getRandomNumber($min = 1, $max = 1000)
        {
            return rand($min, $max);
        }

        function addRandomNumberToCurrentTime()
        {
            return $this->getCurrentTime() + $this->getRandomNumber();
        }

        function methodWithoutAFunction()
        {
            return true;
        }

        /**
         * @param int $bail
         * @return int
         *
         * @throws \Exception
         */
        function bailIfWrongNumber($bail = 0)
        {
            $result = rand(1, 1000);
            if ($result == $bail) {
                throw new \Exception();
            }
            return $result;
        }

        function getServerRandomNumber()
        {
            return rand(1, 1000) + $this->getServerTime();
        }
    }

}
