<?php 
require "../core/function.php";?>
<?php 
class User {
    private $username;
    private $email;
    private $password;
    private $role;
    private $date;

    public function __construct($username,$email,$password)
    {
        $this->username=$username;
        $this->email=$email;
        $this->password=$password;
        $this->role='user';
        $this->date=date("Y-m-d H:i:s");
    }
    // Getters and setters
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getRole() {
        return $this->role;
    }

    public function setDate($date) {
        $this->date = $date;
    }
    public function signup() {
        $values =[];
        $values['username']=$this->username;
        $values['email'] = $this->email;
        $values['password'] = password_hash($this->password, PASSWORD_DEFAULT);
        $values['role'] = $this->role;
        $values['date'] = $this->date;
        $query = "insert into users(username,email,password,role,date) values(:username,:email,:password,:role,:date)";
        db_query($query, $values);
        $value['email'] =  $this->email;

        $query1 = "select * from users where email = :email";
        $row = db_query_one($query1, $value);
        authenticate($row);
        header("Location: /music_for_you/public/home");

    }

    public function login()
    {
      
            $values = [];
            $values['email'] = $this->email;
            $query = "select * from users where email = :email";
            $row = db_query_one($query, $values);
        
            if (!empty($row)) {
        
                if (password_verify($this->password, $row['password'])) {
                    authenticate($row);
                    if ($row['role'] == "admin")
                        header("Location: /music_for_you/public/admin");

                    else
                        if ($row['role'] == "manager")
                        header("Location: /music_for_you/public/manager");
                        else
                        header("Location: /music_for_you/public/home");
                    }
            }
            message("Wrong email or password");
        }
    
}
