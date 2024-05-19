<?php 
require "../core/function.php";?>
<?php 
  class Contact{
    private $name;
    private $email;
    private $number;
    private $message;
    public function __construct($name, $email, $number, $message) {
        $this->name = $name;
        $this->email = $email;
        $this->number = $number;
        $this->message = $message;
    }
    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }
    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function getNumber() {
        return $this->number;
    }
    public function setNumber($number) {
        $this->number = $number;
    }
    public function getMessage() {
        return $this->message;
    }
    public function setMessage($message) {
        $this->message = $message;
    }
    public function sendMessage() {
        $values = [];
        $values['name'] = $this->name;
        $values['email'] = $this->email;
        $values['number'] = $this->number;
        $values['message'] = $this->message;
        $query = "insert into contact (name, email, number, message) values (:name, :email, :number, :message)";
        db_query($query, $values);
    }
  }