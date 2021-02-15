<?php

class Email{	
	protected $from;
	protected $toList;
	protected $ccList;
	protected $bccList;	
	protected $subject;
	protected $content;
	protected $htmlContent;
	protected $attachments = array();
	protected $customHeaders = array();
 
	// setter
	public function setFrom( $from ){$this->from = $from;}	
	public function setToList( $toList ){$this->toList = $toList;}	
	public function setCcList( $ccList ){$this->ccList = $ccList;}	
	public function setBccList( $bccList ){$this->bccList = $bccList;}	
	public function setSubject( $subject ){$this->subject = $subject;}	
	public function setContent( $content ){$this->content = $content;}	
	public function setHtmlContent( $htmlContent ){$this->htmlContent = $htmlContent;}	
	public function setCustomHeader( $customHeader ){$this->customHeader = $customHeader;}
    public function setAttachments( $attachments ){$this->attachments = $attachments;}
    public function addAttachment($filePath, $fileName = null, $type = null){

	    $mime_file_type = null;
	    $fileName = !is_null($fileName) ? $fileName : basename($filePath);

	    if (function_exists('mime_content_type')){
            $mime_file_type = mime_content_type($filePath);
        }

	    if (!is_null($type)){
	        $mime_file_type = $type;
        }

	    if (!is_null($mime_file_type) && file_exists($filePath)) {
            $attachment = array(
                "name"    => $fileName,
                "type"    => $mime_file_type,
                "content" => base64_encode(file_get_contents($filePath))
            );
            $this->attachments[] = $attachment;
        }
    }

	// getter
	public function getFrom(){return $this->from;}
	public function getToList(){return $this->toList;}
	public function getCcList(){return $this->ccList;}
	public function getBccList(){return $this->bccList;}
	public function getSubject(){return $this->subject;}
	public function getContent(){return $this->content;}
	public function getHtmlContent(){return $this->htmlContent;}	
	public function getCustomHeader(){return json_encode($this->customHeaders);}
	

	public function addCustomHeader($key,$value){
		$this->customHeaders[$key] = $value; 
	}
	
	public function removeCustomHeader($key){
		unset($this->customHeaders[$key]);	
	}
	
	public function getPostParameters(){
		return array(
		            'from' => $this->from,
				    'to' => $this->toList,
				    'bcc' => $this->bccList,
				    'cc' => $this->ccList,
				    'subject' => $this->subject,
				    'content' => $this->content,
				    'html_content' => $this->htmlContent,
				    'attachments' => $this->attachments,
				    'custom_headers' => $this->getCustomHeader()
		        );
	}
	
};
