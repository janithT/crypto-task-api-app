<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{

    /**
     * Register a new user.
     *
     * @param array $data
     * @return User
     */
    public function userRegister(array $data) : User
    {

        $fullName = $data['first_name'] . ' ' . $data['last_name'];

        DB::beginTransaction(); // to confirm user get the welcome email first.  

        try {
            $user = User::create([
                'first_name'   => $data['first_name'],
                'last_name'    => $data['last_name'],
                'name'         => $fullName,
                'email'        => $data['email'],
                'password'     => Hash::make($data['password']),
                'enable_login' => $data['enable_login'] ?? true,
            ]);

            $emailPayload = [
                'to'       => $user->email,
                'subject'  => 'Welcome to Task App',
                'template' => 'emails.welcome',
                'name'     =>  $fullName,
            ];

            sendSystemEmail($emailPayload);

            DB::commit();
            return $user;

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('User registration failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    /**
     * Log in a user and return a JWT token.
     *
     * @param array $credentials
     * @return string
     * @throws \Exception
     */
    public function userLogin(array $credentials): array
    {

        $token = JWTAuth::attempt($credentials);
        if (!$token) {
            return ['error' => 'Invalid credentials'];
        }

        return $this->withJwTToken(['token' => $token]);
    }


    /**
     * Logout user
     * 
     */
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }


    /**
     * Bind extra details
     */
    private function withJwTToken(array $data): array
    {
        $data['expires_in'] = JWTAuth::factory()->getTTL() * 120;  // set on env later
        $data['token_type'] = 'Bearer';
        $data['user'] = JWTAuth::user();
        return $data;
    }
}
