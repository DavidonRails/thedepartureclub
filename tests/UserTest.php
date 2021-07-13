<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{

    use DatabaseMigrations;


    public function testRegister()
    {
        Artisan::call('db:seed', ['--class' => 'BillingPackagesSeeder']);
        
        $inputs = [
            'email' => 'srdjan.mitrovic+hobojetunittest@gmail.com',
            'password' => 'loodloo',
            'first_name' => 'Ime',
            'last_name' => 'Prezime',
            'type' => 'ios',
            'tier' => '2'
        ];

        $response = $this->call('POST', 'api/auth/register', $inputs);
        $data = $this->parseJson($response);

        $this->assertEquals($data->status, 1);
        $this->assertEquals($data->responseData->user_id, 1);
        $this->assertArrayHasKey('token', (array)$data->responseData);


    }

    public function testLogin() 
    {
        Artisan::call( 'db:seed', [ '--class' => 'UserSeeder' ] );

        $inputs = [
            'email'    => 'admin@hobojet.com',
            'password' => 'loodloo',
            'type'     => 'ios',
            'remember' => 1
        ];

        $response = $this->call( 'POST', 'api/auth/login', $inputs );
        $data     = $this->parseJson( $response );

        $this->assertEquals( $data->status, 1 );
        $this->assertEquals( $data->responseData->user->user_id, 1 );
        $this->assertArrayHasKey( 'token', (array) $data->responseData );


    }
}
