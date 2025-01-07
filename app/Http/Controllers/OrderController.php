<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public function index()
    {
        $orders = Order::all();
        return response()->json([
            'success' => true,
            'message' =>' List Semua Order',
            'data'    => $orders
        ], 200);
    }


    public function store(Request $request)
    {
        // Validasi input
        $validatedData = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'service_id' => 'required|integer',
            'order_date' => 'required|date',
            'status' => 'required|string',
            'total_price' => 'required|integer',
        ]);

        // Jika validasi gagal
        if ($validatedData->fails()) {
            $errors = $validatedData->errors();
            $errorMessages = [];
            foreach ($errors->all() as $message) {
                $errorMessages[] = $message;
            }
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errorMessages,
            ], 422);
        }

        // Membuat order baru
        $order = Order::create([
            'user_id' => $request->input('user_id'),
            'service_id' => $request->input('service_id'),
            'order_date' => $request->input('order_date'),
            'status' => $request->input('status'),
            'total_price' => $request->input('total_price')
        ]);

        // Jika order berhasil dibuat
        if ($order) {
            $mail = new PHPMailer(true);
            try {
                // Set pengaturan SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'maulanaikhsan5311@gmail.com';
                $mail->Password = 'hzvtupfyoiknopjo';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Pengaturan pengirim dan penerima
                $mail->setFrom('anjay1435@gmail.com', 'Beauty-care');
                $mail->addAddress('jaerimjaerim05@gmail.com');

                // Konten email
                $mail->isHTML(true);
                $mail->Subject = 'Order Confirmation';
                $mail->Body = '
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f9f9f9;
                            margin: 0;
                            padding: 20px;
                        }
                        .email-container {
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #ffffff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        }
                        .email-header {
                            text-align: center;
                            font-size: 24px;
                            color: #fff;
                            background-color: #4CAF50; /* Warna hijau untuk header */
                            padding: 10px;
                            border-radius: 5px;
                        }
                        .email-body {
                            font-size: 16px;
                            color: #333;
                            line-height: 1.6;
                        }
                        .order-details {
                            margin-top: 20px;
                            padding: 15px;
                            background-color: #eaf1e3; /* Warna latar belakang hijau muda */
                            border-radius: 8px;
                            border: 1px solid #c6e1c6; /* Garis border hijau lembut */
                        }
                        .order-details p {
                            margin: 8px 0;
                        }
                        .email-footer {
                            margin-top: 20px;
                            text-align: center;
                            font-size: 12px;
                            color: #888;
                        }
                        .btn {
                            display: inline-block;
                            background-color: #4CAF50; /* Warna hijau tombol */
                            color: white;
                            padding: 10px 20px;
                            text-decoration: none;
                            border-radius: 5px;
                            margin-top: 20px;
                            text-align: center;
                        }
                        .btn:hover {
                            background-color: #45a049; /* Efek hover hijau lebih gelap */
                        }
                    </style>
                </head>
                <body>
                    <div class="email-container">
                        <div class="email-header">
                            Order Confirmation
                        </div>
                        <div class="email-body">
                            <p>Dear Customer,</p>
                            <p>Thank you for your order. Below are your order details:</p>
                            <div class="order-details">
                                <p><strong>Order ID:</strong> ' . $order->id . '</p>
                                <p><strong>User ID:</strong> ' . $order->user_id . '</p>
                                <p><strong>Service ID:</strong> ' . $order->service_id . '</p>
                                <p><strong>Order Date:</strong> ' . $order->order_date . '</p>
                                <p><strong>Status:</strong> ' . $order->status . '</p>
                                <p><strong>Total Price:</strong> Rp.' . number_format($order->total_price, 2) . '</p>
                            </div>
                            <p>If you have any questions, feel free to reach out to us.</p>
                            <p>Best regards,</p>
                            <p>Beauty-care Team</p>
                            <a href="#" class="btn">View Order Details</a>
                        </div>
                        <div class="email-footer">
                            <p>&copy; ' . date("Y") . ' Beauty-care. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
                ';
                $mail->send();
                return response()->json([
                    'success' => true,
                    'message' => 'Order berhasil dibuat dan email dikirim',
                    'data' => $order
                ], 201);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => "Gagal mengirim email: {$mail->ErrorInfo}"
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal membuat order'
        ], 500);
    }




    public function show($id)
    {
        $order = Order::find($id);

        if ($order) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Order',
                'data'      => $order
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Order Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request , $id){
        $validator = Validator::make($request->all(), [
            'user_id'      => 'required',
            'service_id'       => 'string',
            'order_date'             => 'date',
            'status'          => 'string',
            'total_price'       => 'integer'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {
            $order = Order::whereId($id)->update([
                'user_id'     => $request->input('user_id'),
                'service_id'   => $request->input('service_id'),
                'order_date'   => $request->input('order_date'),
                'status'   => $request->input('status'),
                'total_price'   => $request->input('total_price'),
            ]);

            if ($order) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Berhasil Diupdate!',
                    'data' => $order
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Order Gagal Diupdate!',
                ], 400);
            }

        }
    }

    public function destroy($id)
    {
        $order = Order::whereId($id)->delete();
        if($order){
            return response()->json([
                'success' => true,
                'message' => 'Order Berhasil Dihapus!',
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Order Gagal Dihapus!',
            ], 200);
        }


    }


}
