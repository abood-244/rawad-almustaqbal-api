<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Boot app
$request = Illuminate\Http\Request::create('/api/services', 'GET');
$response = $kernel->handle($request);
$services = json_decode($response->getContent(), true)['data'] ?? [];
$serviceId = $services[0]['id'] ?? 1;

echo "--- Testing Orders Module ---\n";

// 1. Create Valid Order
echo "1. Create valid order:\n";
$req = Illuminate\Http\Request::create('/api/orders', 'POST', [
    'customer_name' => 'John Doe',
    'phone' => '0501234567',
    'location' => 'Riyadh',
    'service_id' => $serviceId,
    'description' => 'Need help with plumbing'
]);
$req->headers->set('Accept', 'application/json');
$res = $kernel->handle($req);
echo "Status: " . $res->getStatusCode() . "\n";
echo "Response: " . $res->getContent() . "\n\n";
$orderId = json_decode($res->getContent(), true)['data']['id'] ?? null;

// 2. Create Order without service
echo "2. Create order without service:\n";
$req2 = Illuminate\Http\Request::create('/api/orders', 'POST', [
    'customer_name' => 'John Doe',
    'phone' => '0501234567',
    'location' => 'Riyadh',
    'description' => 'Need help'
]);
$req2->headers->set('Accept', 'application/json');
$res2 = $kernel->handle($req2);
echo "Status: " . $res2->getStatusCode() . "\n";
echo "Response: " . $res2->getContent() . "\n\n";

// 3. Create Order without phone
echo "3. Create order without phone:\n";
$req3 = Illuminate\Http\Request::create('/api/orders', 'POST', [
    'customer_name' => 'John Doe',
    'location' => 'Riyadh',
    'service_id' => $serviceId,
    'description' => 'Need help'
]);
$req3->headers->set('Accept', 'application/json');
$res3 = $kernel->handle($req3);
echo "Status: " . $res3->getStatusCode() . "\n";
echo "Response: " . $res3->getContent() . "\n\n";

// 4. Update order with invalid status (Assuming we have admin token, we will simulate the Request)
echo "4. Update order with invalid status:\n";
if ($orderId) {
    $req4 = Illuminate\Http\Request::create('/api/orders/'.$orderId.'/status', 'PATCH', [
        'status' => 'invalid_status'
    ]);
    $req4->headers->set('Accept', 'application/json');
    $user = \App\Models\User::first();
    $req4->setUserResolver(function() use ($user) { return $user; });
    $res4 = $kernel->handle($req4);
    echo "Status: " . $res4->getStatusCode() . "\n";
    echo "Response: " . $res4->getContent() . "\n\n";
}

// 5. Update order with valid status
echo "5. Update order with valid status:\n";
if ($orderId) {
    $req5 = Illuminate\Http\Request::create('/api/orders/'.$orderId.'/status', 'PATCH', [
        'status' => 'completed'
    ]);
    $req5->headers->set('Accept', 'application/json');
    $user = \App\Models\User::first();
    $req5->setUserResolver(function() use ($user) { return $user; });
    $res5 = $kernel->handle($req5);
    echo "Status: " . $res5->getStatusCode() . "\n";
    echo "Response: " . $res5->getContent() . "\n\n";
}

// 6. Test Pagination
echo "6. Test Pagination:\n";
$req6 = Illuminate\Http\Request::create('/api/orders', 'GET');
$req6->headers->set('Accept', 'application/json');
$user = \App\Models\User::first();
$req6->setUserResolver(function() use ($user) { return $user; });
$res6 = $kernel->handle($req6);
echo "Status: " . $res6->getStatusCode() . "\n";
$data = json_decode($res6->getContent(), true);
echo "Has pagination meta: " . (isset($data['meta']) ? 'Yes' : 'No') . "\n\n";

// 7. Test Rate Limiting
echo "7. Test Rate Limiting:\n";
for ($i=0; $i<4; $i++) {
    $reqL = Illuminate\Http\Request::create('/api/orders', 'POST', [
        'customer_name' => 'Spammer',
        'phone' => '0501234567',
        'location' => 'Riyadh',
        'service_id' => $serviceId,
        'description' => 'Spam'
    ]);
    $reqL->server->set('REMOTE_ADDR', '127.0.0.1');
    $reqL->headers->set('Accept', 'application/json');
    $resL = $kernel->handle($reqL);
    echo "Attempt " . ($i+1) . " Status: " . $resL->getStatusCode() . "\n";
}
