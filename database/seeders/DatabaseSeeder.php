<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\Category;
use App\Models\Portofolio;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(5)->create();

        $status = ['pending' , 'canceled' , 'confirmed' , 'complete'];

        for($i = 1; $i < 2; $i++) {
            $category = new Category();
            $category->category_name = 'Category - ' . $i;
            $category->slug = Str::slug($category->category_name);
            $category->save();

            $portofolio = new Portofolio();
            $portofolio->user_id = rand(1,2);
            $portofolio->title = "Bridal Style";
            $portofolio->description = 'lorem123';
            $portofolio->image = "123";
            $portofolio->save();

            $service = new Service();
            $service->service_name = 'test ' . $i;
            $service->description = 'description ' . $i;
            $service->price = 50000;
            $service->duration = rand(10,100);
            $service->save();

            $order = new Order();
            $order->user_id = rand(1,2);
            $order->service_id = 1;
            $order->order_date = '2025-01-20';
            $order->status = $status[array_rand($status)];
            $order->total_price = rand(5000,50000);
            $order->save();

        }
    }
}
