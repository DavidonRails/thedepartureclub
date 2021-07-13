<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;


class GetFlightsTest extends TestCase
{

    use DatabaseMigrations;

    public function testGetFlightsList()
    {

        Artisan::call( 'db:seed', [ '--class' => 'FlightsSeeder' ] );

        $response = $this->call('POST', 'api/flights/get');
      
        $data = $this->parseJson($response);

        $this->assertEquals( $data->status, 1 );
        $this->assertEquals( $data->responseData->pagination->current_page, 1 );
        $this->assertCount(10, $data->responseData->data);

        $first = (array)$data->responseData->data[0];

        $this->assertNotEmpty($first['route_destination']);
        $this->assertNotEmpty($first['route_origin']);
        $this->assertNotEmpty($first['flight_start']);
        $this->assertNotEmpty($first['price']);
        $this->assertNotEmpty($first['aircraft_image']);
        $this->assertNotEmpty($first['seats']);
        $this->assertNotEmpty($first['aircraft_name']);
        $this->assertNotEmpty($first['flight_id']);
        $this->assertNotEmpty($first['period_from']);
        $this->assertNotEmpty($first['period_to']);

    }

    public function testGetFlightDetails()
    {
        Artisan::call( 'db:seed', [ '--class' => 'FlightsSeeder' ] );

        $response = $this->call('POST', 'api/flights/get/details', [
            'flight_id' => 1
        ]);

        $data = $this->parseJson($response);

        $this->assertEquals( $data->status, 1 );

        $flight = (array)$data->responseData;

        $this->assertNotEmpty($flight['destination_location']);
        $this->assertNotEmpty($flight['origin_location']);
        $this->assertNotEmpty($flight['date']);
        $this->assertNotEmpty($flight['aircraft_image']);
        $this->assertNotEmpty($flight['seats']);
        $this->assertNotEmpty($flight['aircraft_name']);
        $this->assertNotEmpty($flight['period_from']);
        $this->assertNotEmpty($flight['period_to']);
        $this->assertNotEmpty($flight['price']);

    }
    
}
