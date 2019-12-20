<?php
namespace SB;
use Exception;
use Session;

class Email{
	private $to = [];
	private $name = [];
	private $subject = "New E-Mail";
	private $body = "Hi, You have new mail found!";
	private $headers = null;
	private $isHtml = false;
	private $cc = [];
	private $from = null;
	private $mail = null;

	
	public function __construct(){
		$this->headers = "MIME-Version: 1.0" . "\r\n";
		$this->headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$this->mail = new Mailer(true);
		$this->mail->SMTPDebug = 0;                      // Enable verbose debug output
		$this->mail->isSMTP();                                            // Send using SMTP
		$this->mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
		$this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$this->mail->Username   = '';                     // SMTP username
		$this->mail->Password   = '';                               // SMTP password
		$this->mail->SMTPSecure = false;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
		$this->mail->Port       = 587; 
	}

    public static function to($to, $name = ""){
		$obj = new static;
		array_push($obj->to, $to);
		array_push($obj->name, $name);
		return $obj;
	}

    public function cc($cc){
		
		array_push($obj->cc, $cc);
		
		return $this;
	}
	
	public function subject($subject = null){
		if($subject == null)
			$this->subject = "New E-Mail";
		else
			$this->subject = $subject;
		
		return $this;
	}
	
	public function message($body = null){
		if($body == null)
			$this->body = "Hi, You have new mail found!";
		else
			$this->body = $body;
		
		return $this;
	}

	public function post(){
		$to = implode(',',$this->to);
		if($this->isHtml){
			$mail =  mail($to,$this->subject,$this->body,$this->headers);
		}else{
			$mail = mail($to,$this->subject,$this->body);
		}
		return $mail;
	}

	public function view($view = null,$data = null){
		if($view == null)
			$this->body = "Hi, You have new mail found!";
		else{
			$view_loader = new \Twig\Loader\FilesystemLoader('./views/emails');
			$twig = new \Twig\Environment($view_loader);
			$res['app'] = ['name'=>APP_NAME,'url'=>URL];
			$res['app']['session'] = Session::all();
			if($data != null)
			$res['app']['data'] = $data;
			$view = $view.'.html';
			$this->body = $twig->render($view, $res);
		}
		$this->html();
		return $this;
	}
	
	public function from($from = null,$name = ""){
		if($from != null){
		$this->mail->setFrom($from, $name);
		$this->from = true;
		}
		
		return $this;
	}
	
	public function html($value = true){
		$this->isHtml = $value;
		$this->mail->isHTML(true); 
		return $this;
	}
	
	



	public function send(){
		

try {
	
	if($this->from == null)
		$this->mail->setFrom(FROM_MAIL,APP_NAME);


	foreach ($this->to as $key => $value) {
		$this->mail ->addAddress($value, $this->to[$key]);     // Add a recipient
	}	
            
   // $this->mail ->addReplyTo('info@example.com', 'Information');
    //$this->mail ->addCC($this->cc);
    //$this->mail ->addBCC('bcc@example.com');

    // Attachments
    //$this->mail ->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
   // $this->mail ->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
                                     // Set email format to HTML
    $this->mail ->Subject = $this->subject;
    $this->mail ->Body    =  $this->body;
    //$this->mail ->AltBody = 'This is the body in plain text for non-HTML mail clients';

   
	
	
		
	

	$this->mail ->send();
	return true;

	} catch (Exception $e) {
    	return  "Message could not be sent. Mailer Error: {$this->mail ->ErrorInfo}";
	}

	}
	

}

