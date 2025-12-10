<?php
class UserModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // --------------------------
    // Email & Auth
    // --------------------------

    public function emailExists($email)
    {
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --------------------------
    // Registration
    // --------------------------

    public function registerJobSeeker($user_type, $fullname, $email, $password, $disability, $disability_subcategory, $birthday, $highest_education, $experience_status, $experience_years, $experience_field, $address, $phone, $preferred_work, $skills, $resumePath, $shift, $qr_id)
{
    $stmt = $this->conn->prepare("
        INSERT INTO users (
            user_type, fullname, email, password, disability, disability_subcategory,
            birthday, highest_education, experience_status, experience_years, experience_field,
            location, contact_number, preferred_work, skills, resume, shift, qr_id
        ) VALUES (
            :user_type, :fullname, :email, :password, :disability, :disability_subcategory,
            :birthday, :highest_education, :experience_status, :experience_years, :experience_field,
            :location, :contact_number, :preferred_work, :skills, :resume, :shift, :qr_id
        )
    ");

    return $stmt->execute([
        ':user_type' => $user_type,
        ':fullname' => $fullname,
        ':email' => $email,
        ':password' => $password,
        ':disability' => $disability,
        ':disability_subcategory' => $disability_subcategory,
        ':birthday' => $birthday,
        ':highest_education' => $highest_education,
        ':experience_status' => $experience_status,
        ':experience_years' => $experience_years,
        ':experience_field' => $experience_field,
        ':location' => $address,
        ':contact_number' => $phone,
        ':preferred_work' => $preferred_work,
        ':skills' => $skills,
        ':resume' => $resumePath,
        ':shift' => $shift,
        ':qr_id' => $qr_id
    ]);
}


    public function registerClient($user_type, $fullname, $email, $password)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO users (user_type, fullname, email, password)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$user_type, $fullname, $email, $password]); // already hashed
    }

    // --------------------------
    // Profile Update
    // --------------------------

    public function updateUser($id, $fullname, $email, $description, $location, $disability, $contact_number, $imgPath = null)
    {
        $sql = "UPDATE users 
                SET fullname = :fullname, email = :email, description = :description, 
                    location = :location, disability = :disability, contact_number = :contact_number";

        $params = [
            ':fullname' => $fullname,
            ':email' => $email,
            ':description' => $description,
            ':location' => $location,
            ':disability' => $disability,
            ':contact_number' => $contact_number
        ];

        if ($imgPath !== null) {
            $sql .= ", img = :img";
            $params[':img'] = $imgPath;
        }

        $sql .= " WHERE user_id = :user_id";
        $params[':user_id'] = $id;

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // --------------------------
    // Password Reset
    // --------------------------

    public function updatePassword($email, $hashed_password)
    {
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        return $stmt->execute([$hashed_password, $email]);
    }

    public function saveResetToken($email, $token, $expires_at)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO password_resets (email, token, expires_at)
            VALUES (:email, :token, :expires_at)
            ON DUPLICATE KEY UPDATE token = :token2, expires_at = :expires2
        ");

        return $stmt->execute([
            ':email' => $email,
            ':token' => $token,
            ':expires_at' => $expires_at,
            ':token2' => $token,
            ':expires2' => $expires_at
        ]);
    }

    public function getResetByToken($token)
    {
        $stmt = $this->conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteResetToken($email)
    {
        $stmt = $this->conn->prepare("DELETE FROM password_resets WHERE email = ?");
        return $stmt->execute([$email]);
    }
}
?>
