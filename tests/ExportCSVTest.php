<?php

namespace PHPForms\Tests;

class ExportCSVTest extends \PHPUnit\Framework\TestCase
{
    /**
     * CSV file path
     *
     * @var string
     */
    private $file = './test.csv';

    public function tearDown()
    {
        // Delete the test.csv file
        @unlink($this->file);
    }

    public function testWeCanExportDataToANewCsvFile()
    {
        $tests = [
            [
                'data' => [
                    'first_name' => 'Mehdi',
                    'last_name'  => 'Bounya',
                    'comment'    => 'this is a test',
                ],
                'expected_result' => "first_name,last_name,comment\n"
                                     ."Mehdi,Bounya,\"this is a test\"\n",
            ],
            [
                'data' => [
                    'First name' => 'Mehdi',
                    'Last, name'  => 'Bounya',
                    'A,Comment;"'    => 'this is a test',
                ],
                'expected_result' => "\"First name\",\"Last, name\",\"A,Comment;\"\"\"\n"
                                     ."Mehdi,Bounya,\"this is a test\"\n",
            ],
        ];

        // Passing data via the constructor
        foreach ($tests as $test) {
            $csv = new \PHPForms\ExportCSV($this->file, $test['data']);
            $this->assertEquals(true, $csv->export());

            // Get file content
            $file = file_get_contents($this->file);
            $this->assertEquals($test['expected_result'], $file);

            $this->tearDown();
        }

        // Setting file using the setFilePath method
        foreach ($tests as $test) {
            $csv = new \PHPForms\ExportCSV('', $test['data']);
            $csv->setFilePath($this->file);
            $this->assertEquals(true, $csv->export());

            // Get file content
            $file = file_get_contents($this->file);
            $this->assertEquals($test['expected_result'], $file);

            $this->tearDown();
        }

        // Passing data using the setData method
        foreach ($tests as $test) {
            $csv = new \PHPForms\ExportCSV($this->file);

            foreach ($test['data'] as $field => $value) {
                $csv->setData($field, $value);
            }

            $this->assertEquals(true, $csv->export());

            // Get file content
            $file = file_get_contents($this->file);
            $this->assertEquals($test['expected_result'], $file);

            // Delete the file
            $this->tearDown();
        }
    }

    public function testWeCanAppendDataToCsvFile()
    {
        $tests = [
            [
                'data' => [
                    'first_name' => 'Mehdi',
                    'last_name'  => 'Bounya',
                    'comment'    => 'this is a test',
                ],
                'expected_result' => "first_name,last_name,comment\n"
                                     ."Mehdi,Bounya,\"this is a test\"\n",
            ],
            [
                'data' => [
                    'first_name' => 'Hello',
                    'last_name'  => 'World',
                    'comment'   => 'Another test',
                ],
                'expected_result' => "first_name,last_name,comment\n"
                                     ."Mehdi,Bounya,\"this is a test\""
                                     ."\nHello,World,\"Another test\"\n",
            ],
        ];

        foreach ($tests as $test) {
            $csv = new \PHPForms\ExportCSV($this->file, $test['data']);
            $this->assertEquals(true, $csv->export());

            // Get file content
            $file = file_get_contents($this->file);
            $this->assertEquals($test['expected_result'], $file);
        }
    }

    public function testWeCanMapDataToNewFile()
    {
        $tests = [
            [
                'data' => [
                    'first_name' => 'Mehdi',
                    'last_name'  => 'Bounya',
                    'comment'    => 'this is a test',
                ],
                'expected_result' => "\"First name\",\"Last name\",comment\n"
                                     ."Mehdi,Bounya,\"this is a test\"\n",
            ],
            [
                'data' => [
                    'first_name' => 'Hello',
                    'last_name'  => 'World',
                    'comment'   => 'Another test',
                ],
                'expected_result' => "\"First name\",\"Last name\",comment\n"
                                     ."Hello,World,\"Another test\"\n",
            ],
        ];

        $map = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
        ];

        foreach ($tests as $test) {
            $csv = new \PHPForms\ExportCSV($this->file, $test['data']);
            $csv->setMap($map);
            $this->assertEquals(true, $csv->export());

            // Get file content
            $file = file_get_contents($this->file);
            $this->assertEquals($test['expected_result'], $file);
            $this->tearDown();
        }
    }

    public function testWeCanMapDataToExistingCsv()
    {
        $tests = [
            [
                'data' => [
                    'first_name' => 'Mehdi',
                    'last_name'  => 'Bounya',
                    'comment'    => 'this is a test',
                ],
                'expected_result' => "\"First name\",\"Last name\",comment\n"
                                     ."Mehdi,Bounya,\"this is a test\"\n",
            ],
            [
                'data' => [
                    'first_name' => 'Hello',
                    'last_name'  => 'World',
                    'comment'   => 'Another test',
                ],
                'expected_result' => "\"First name\",\"Last name\",comment\n"
                                     ."Mehdi,Bounya,\"this is a test\""
                                     ."\nHello,World,\"Another test\"\n",
            ],
        ];
        $map = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
        ];

        foreach ($tests as $test) {
            $csv = new \PHPForms\ExportCSV($this->file, $test['data']);
            $csv->setMap($map);
            $this->assertEquals(true, $csv->export());

            // Get file content
            $file = file_get_contents($this->file);
            $this->assertEquals($test['expected_result'], $file);
        }
    }

    public function testWeCanInsertUnorderedDataToExistingFile()
    {
        // Create starter file
        $data = [
            'first_name' => 'Mehdi',
            'last_name'  => 'Bounya',
            'comment'    => 'This is a test',
        ];
        $csv = new \PHPForms\ExportCSV($this->file, $data);
        $csv->export();

        $tests = [
            [
                'data' => [
                    'comment'   => 'Another test',
                    'first_name' => 'Hello',
                    'last_name'  => 'World',
                ],
                'expected_result' => "first_name,last_name,comment\n"
                                     ."Mehdi,Bounya,\"This is a test\"\n"
                                     ."Hello,World,\"Another test\"\n",
            ],
        ];

        foreach ($tests as $test) {
            $csv = new \PHPForms\ExportCSV($this->file, $test['data']);
            $this->assertEquals(true, $csv->export());

            // Get file content
            $file = file_get_contents($this->file);
            $this->assertEquals($test['expected_result'], $file);
        }
    }

    public function testWeCanInsertUnorderedDataToExistingFileWhileUsingMap()
    {
        $map = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
        ];

        // Create starter file
        $data = [
            'first_name' => 'Mehdi',
            'last_name'  => 'Bounya',
            'comment'    => 'This is a test',
        ];
        $csv = new \PHPForms\ExportCSV($this->file, $data);
        $csv->setMap($map);
        $csv->export();

        $tests = [
            [
                'data' => [
                    'comment'   => 'Another test',
                    'first_name' => 'Hello',
                    'last_name'  => 'World',
                ],
                'expected_result' => "\"First name\",\"Last name\",comment\n"
                                     ."Mehdi,Bounya,\"This is a test\""
                                     ."\nHello,World,\"Another test\"\n",
            ],
        ];

        foreach ($tests as $test) {
            $csv = new \PHPForms\ExportCSV($this->file, $test['data']);
            $csv->setMap($map);
            $this->assertEquals(true, $csv->export());

            // Get file content
            $file = file_get_contents($this->file);
            $this->assertEquals($test['expected_result'], $file);
        }
    }
}
