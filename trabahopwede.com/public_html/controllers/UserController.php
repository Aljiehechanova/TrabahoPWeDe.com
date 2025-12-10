<?php
include '../config/db_connection.php';
require_once '../models/UserModel.php';

class UserController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new UserModel($db);
    }

    // ✅ Login
    public function login($email, $password)
    {
        $user = $this->userModel->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    // ✅ Register Job Seeker
   public function registerJobSeeker($user_type, $first_name, $last_name, $suffix, $email, $password,
    $disability, $disability_subcategory, $birthday, $highest_education, 
    $experience_status, $experience_years, $experience_field,
    $address, $phone, $preferred_work, $skills, $resumePath, $shift, $qr_id)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Invalid email format.";
    if ($this->userModel->getUserByEmail($email)) return "This email is already registered.";
    if (!checkdnsrr(substr(strrchr($email, "@"), 1), "MX")) return "The email domain does not exist.";

    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        return "Password must be at least 8 characters long, include 1 uppercase, 1 lowercase, 1 number, and 1 special character.";
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $fullname = trim($first_name . ' ' . $last_name . ($suffix ? ' ' . $suffix : ''));

    return $this->userModel->registerJobSeeker(
        $user_type, $fullname, $email, $hashedPassword, $disability, $disability_subcategory,
        $birthday, $highest_education, $experience_status, $experience_years, $experience_field,
        $address, $phone, $preferred_work, $skills, $resumePath, $shift, $qr_id
    ) ? true : "Failed to register. Please try again.";
}


    // ✅ Register Client
    public function registerClient($user_type, $fullname, $email, $password)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Invalid email format.";
        $domain = substr(strrchr($email, "@"), 1);
        if (!checkdnsrr($domain, "MX")) return "The email domain does not exist.";
        if ($this->userModel->getUserByEmail($email)) return "This email is already registered.";

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
            return "Password must be at least 8 characters long, include 1 uppercase, 1 lowercase, 1 number, and 1 special character.";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return $this->userModel->registerClient(
            $user_type, $fullname, $email, $hashedPassword
        ) ? true : "Failed to register. Please try again.";
    }

    public function emailExists($email)
    {
        return $this->userModel->getUserByEmail($email) ? true : false;
    }

    public function updateProfile($user_id, $fullname, $email, $description, $location, $disability, $contact_number, $imgPath = null)
    {
        return $this->userModel->updateUser($user_id, $fullname, $email, $description, $location, $disability, $contact_number, $imgPath);
    }

    public function updatePasswordByEmail($email, $hashed_password)
    {
        return $this->userModel->updatePassword($email, $hashed_password);
    }

    public function storePasswordResetToken($email, $token, $expires_at)
    {
        return $this->userModel->saveResetToken($email, $token, $expires_at);
    }

    public function getUserByToken($token)
    {
        return $this->userModel->getResetByToken($token);
    }

    public function clearPasswordResetToken($email)
    {
        return $this->userModel->deleteResetToken($email);
    }
}
?>
