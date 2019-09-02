<?php
include 'errorHandler.php';

// Soap Request Class

class OAKHURST_SOAP {
	var $url;
	var $isTest;
	var $SOAPHeader;
	var $SOAPBody = '<soap:Body></soap:Body></soap:Envelope>';
	var $SOAPxml;
	var $SOAPResponeArray;
  var $ErrorArray;
	var $SOAPstatus;
	function OAKHURST_SOAP(bool $isTest, $username, $password) {
		$this->isTest = $isTest;
		$this->url = $isTest ? 'https://apps.softsure.co.za/OaksureUATWS/Service.asmx?WSDL' : 'https://apps.softsure.co.za/OaksureWS/Service.asmx?WSDL';
		$this->SOAPHeader = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Header><AuthHeader xmlns="http://www.softsure.co.za/"><LogonID>'.$username.'</LogonID><Password>'.$password.'</Password></AuthHeader></soap:Header>';
		$this->SOAPxml = $this->SOAPHeader.$this->SOAPBody;
	}
	function _send() {
		$ch = curl_init($this->url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "$this->SOAPxml");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $output);
		$xml = new SimpleXMLElement($response);
		$body = $xml->xpath('//soapBody')[0];
		$array = json_decode(json_encode($body), TRUE);
		return $array;
	}
	function validateID(string $IDNumber, $DateOfBirth) {
		$this->SOAPBody = '<soap:Body><validateID xmlns="http://www.softsure.co.za/"><IDNumber>'.$IDNumber.'</IDNumber><DateOfBirth>'.$DateOfBirth.'</DateOfBirth></validateID></soap:Body></soap:Envelope>';
		$this->SOAPxml = $this->SOAPHeader.$this->SOAPBody;
		$this->SOAPResponseArray = $this->_send()['validateIDResponse']['validateIDResult'];; 
		$this->SOAPstatus = $this->SOAPResponseArray['Status']; 
	}
	function validateBanking($AccountName,$AccountNumber,$BranchCode,$AccountTypeId) {
		$this->SOAPBody = '<soap:Body><validateBanking xmlns="http://www.softsure.co.za/">
		<BankingDetails><AccountName>'.$AccountName.'</AccountName><AccountNumber>'.$AccountNumber.'</AccountNumber><BranchCode>'.$BranchCode.'</BranchCode><AccountTypeId>'.$AccountTypeId.'</AccountTypeId></BankingDetails></validateBanking></soap:Body></soap:Envelope>';
		$this->SOAPxml = $this->SOAPHeader.$this->SOAPBody;
		$this->SOAPResponseArray = $this->_send()['validateBankingResponse']['validateBankingResult'];
		$this->SOAPstatus = $this->SOAPResponseArray['Status']; 
	}
	function validatePolicy(array $PolicyDetails) {
		$SOAPBody = '<soap:Body><validatePolicy xmlns="http://www.softsure.co.za/"><PolicyDetails>
        <InceptionDate>'.$PolicyDetails['InceptionDate'].'</InceptionDate><SignupDate>'.$PolicyDetails['SignupDate'].'</SignupDate><AgentDetail><BrokerCode>'.$PolicyDetails['AgentDetail']['BrokerCode'].'</BrokerCode><SubPartnerCode>'.$PolicyDetails['AgentDetail']['SubPartnerCode'].'</SubPartnerCode><SubPartnerName>'.$PolicyDetails['AgentDetail']['SubPartnerName'].'</SubPartnerName><AgentCode>'.$PolicyDetails['AgentDetail']['AgentCode'].'</AgentCode><AgentName>'.$PolicyDetails['AgentDetail']['AgentName'].'</AgentName><AgentTelephone>'.$PolicyDetails['AgentDetail']['AgentTelephone'].'</AgentTelephone><AgentEmail>'.$PolicyDetails['AgentDetail']['AgentEmail'].'</AgentEmail><VIPCode>'.$PolicyDetails['AgentDetail']['VIPCode'].'</VIPCode></AgentDetail><ProductDetail><Name>'.$PolicyDetails['ProductDetail']['Name'].'</Name><Scheme>'.$PolicyDetails['ProductDetail']['Scheme'].'</Scheme></ProductDetail><InsuredDetail><Surname>'.$PolicyDetails['InsuredDetail']['Surname'].'</Surname><FirstName>'.$PolicyDetails['InsuredDetail']['FirstName'].'</FirstName><Initials>'.$PolicyDetails['InsuredDetail']['Initials'].'</Initials><IDType>'.$PolicyDetails['InsuredDetail']['IDType'].'</IDType><IDNumber>'.$PolicyDetails['InsuredDetail']['IDNumber'].'</IDNumber><GenderCde>'.$PolicyDetails['InsuredDetail']['GenderCde'].'</GenderCde><MaritalCde>'.$PolicyDetails['InsuredDetail']['MaritalCde'].'</MaritalCde><DateOfBirth>'.$PolicyDetails['InsuredDetail']['DateOfBirth'].'</DateOfBirth><AddressLine1>'.$PolicyDetails['InsuredDetail']['AddressLine1'].'</AddressLine1><Suburb>'.$PolicyDetails['InsuredDetail']['Suburb'].'</Suburb><Town>'.$PolicyDetails['InsuredDetail']['Town'].'</Town><Postcode>'.$PolicyDetails['InsuredDetail']['Postcode'].'</Postcode><ContactNo>'.$PolicyDetails['InsuredDetail']['ContactNo'].'</ContactNo><CustomerType>'.$PolicyDetails['InsuredDetail']['CustomerType'].'</CustomerType><UniqueReference>'.$PolicyDetails['InsuredDetail']['UniqueReference'].'</UniqueReference><Language>'.$PolicyDetails['InsuredDetail']['Language'].'</Language><Title>'.$PolicyDetails['InsuredDetail']['Title'].'</Title><MobileNo>'.$PolicyDetails['InsuredDetail']['MobileNo'].'</MobileNo><EmailAddress>'.$PolicyDetails['InsuredDetail']['EmailAddress'].'</EmailAddress><HighestEducation>'.$PolicyDetails['InsuredDetail']['HighestEducation'].'</HighestEducation><RecordAction>'.$PolicyDetails['InsuredDetail']['RecordAction'].'</RecordAction></InsuredDetail><BankingDetail><BranchName>'.$PolicyDetails['BankingDetail']['BranchName'].'</BranchName><BankId>'.$PolicyDetails['BankingDetail']['BranchID'].'</BankId><DebitDay>'.$PolicyDetails['BankingDetail']['DebitDay'].'</DebitDay></BankingDetail><Pets>';
        foreach ($PolicyDetails['Pets'] as $pet) {
        	$SOAPBody .= '<PetDetail><InceptionDate>'.$pet['InceptionDate'].'</InceptionDate><PlanCode>'.$pet['PlanCode'].'</PlanCode><TypeCode>'.$pet['TypeCode'].'</TypeCode><BreedCode>'.$pet['BreedCode'].'</BreedCode><Gender>'.$pet['Gender'].'</Gender><Name>'.$pet['Name'].'</Name><PureBred>'.$pet['PureBred'].'</PureBred><Microchip>'.$pet['Microchip'].'</Microchip><PreCondition>'.$pet['PreCondition'].'</PreCondition><DOB>'.$pet['DOB'].'</DOB><Neutered>'.$pet['Neutered'].'</Neutered><Vaccinations>'.$pet['Vaccinations'].'</Vaccinations><OtherDesc>'.$pet['OtherDesc'].'</OtherDesc><ExcessPercentage>'.$pet['ExcessPercentage'].'</ExcessPercentage><ExcessOption>'.$pet['ExcessOption'].'</ExcessOption><PremiumDetail><RiskPremium>'.$pet['PremiumDetail']['RiskPremium'].'</RiskPremium><ProrataAmount>'.$pet['PremiumDetail']['ProrataAmount'].'</ProrataAmount><GST>'.$pet['PremiumDetail']['GST'].'</GST><ProrataGST>'.$pet['PremiumDetail']['ProrataGST'].'</ProrataGST></PremiumDetail><BenefitID>'.$pet['BenefitID'].'</BenefitID><Extensions xsi:nil="true" /><NYPInd>'.$pet['NYPInd'].'</NYPInd><RecordAction>'.$pet['RecordAction'].'</RecordAction><Status>'.$pet['Status'].'</Status></PetDetail>';
        }
    $SOAPBody .= '</Pets><CampaignId>'.$PolicyDetails['CampaignId'].'</CampaignId><SaleType>'.$PolicyDetails['SaleType'].'</SaleType><PaymentFrequency>'.$PolicyDetails['PaymentFrequency'].'</PaymentFrequency><PaymentTerm>'.$PolicyDetails['PaymentTerm'].'</PaymentTerm><PaymentMethod>'.$PolicyDetails['PaymentMethod'].'</PaymentMethod></PolicyDetails></validatePolicy></soap:Body></soap:Envelope>';
  if (sizeof(errorHandler::policy($PolicyDetails)) <= 0) {
      $this->SOAPBody = $SOAPBody;
      $this->SOAPxml = $this->SOAPHeader.$this->SOAPBody;
      $this->SOAPResponseArray = $this->_send()['validatePolicyResponse']['validatePolicyResult'];
      $this->SOAPstatus = $this->SOAPResponseArray['Status'];
    } else {
      $this->SOAPstatus = 'Failure - Please check ErrorArray';
      $this->ErrorArray = errorHandler::policy($PolicyDetails);
    }
	}
	function inceptPolicy(array $PolicyDetails, string $ExtReference) {
		$SOAPBody = '<soap:Body>
    <inceptPolicy xmlns="http://www.softsure.co.za/">
      <PolicyDetails>
        <InceptionDate>'.$PolicyDetails['InceptionDate'].'</InceptionDate>
        <SignupDate>'.$PolicyDetails['SignupDate'].'</SignupDate>
        <AgentDetail>
          <BrokerCode>'.$PolicyDetails['AgentDetail']['BrokerCode'].'</BrokerCode>
          <SubPartnerCode>'.$PolicyDetails['AgentDetail']['SubPartnerCode'].'</SubPartnerCode>
          <SubPartnerName>'.$PolicyDetails['AgentDetail']['SubPartnerName'].'</SubPartnerName>
          <AgentCode>'.$PolicyDetails['AgentDetail']['AgentCode'].'</AgentCode>
          <AgentName>'.$PolicyDetails['AgentDetail']['AgentName'].'</AgentName>
          <AgentTelephone>'.$PolicyDetails['AgentDetail']['AgentTelephone'].'</AgentTelephone>
          <AgentEmail>'.$PolicyDetails['AgentDetail']['AgentEmail'].'</AgentEmail>
          <VIPCode>'.$PolicyDetails['AgentDetail']['VIPCode'].'</VIPCode>
        </AgentDetail>
        <ProductDetail>
          <Name>'.$PolicyDetails['ProductDetail']['Name'].'</Name>
          <Scheme>'.$PolicyDetails['ProductDetail']['Scheme'].'</Scheme>
        </ProductDetail>
        <InsuredDetail>
          <Surname>'.$PolicyDetails['InsuredDetail']['Surname'].'</Surname>
          <FirstName>'.$PolicyDetails['InsuredDetail']['FirstName'].'</FirstName>
          <Initials>'.$PolicyDetails['InsuredDetail']['Initials'].'</Initials>
          <IDType>'.$PolicyDetails['InsuredDetail']['IDType'].'</IDType>
          <IDNumber>'.$PolicyDetails['InsuredDetail']['IDNumber'].'</IDNumber>
          <GenderCde>'.$PolicyDetails['InsuredDetail']['GenderCde'].'</GenderCde>
          <MaritalCde>'.$PolicyDetails['InsuredDetail']['MaritalCde'].'</MaritalCde>
          <DateOfBirth>'.$PolicyDetails['InsuredDetail']['DateOfBirth'].'</DateOfBirth>
          <AddressLine1>'.$PolicyDetails['InsuredDetail']['AddressLine1'].'</AddressLine1>
          <Suburb>'.$PolicyDetails['InsuredDetail']['Suburb'].'</Suburb>
          <Town>'.$PolicyDetails['InsuredDetail']['Town'].'</Town>
          <Postcode>'.$PolicyDetails['InsuredDetail']['Postcode'].'</Postcode>
          <ContactNo>'.$PolicyDetails['InsuredDetail']['ContactNo'].'</ContactNo>
          <CustomerType>'.$PolicyDetails['InsuredDetail']['CustomerType'].'</CustomerType>
          <UniqueReference>'.$PolicyDetails['InsuredDetail']['UniqueReference'].'</UniqueReference>
          <Language>'.$PolicyDetails['InsuredDetail']['Language'].'</Language>
          <Title>'.$PolicyDetails['InsuredDetail']['Title'].'</Title>
          <MobileNo>'.$PolicyDetails['InsuredDetail']['MobileNo'].'</MobileNo>
          <EmailAddress>'.$PolicyDetails['InsuredDetail']['EmailAddress'].'</EmailAddress>
          <HighestEducation>'.$PolicyDetails['InsuredDetail']['HighestEducation'].'</HighestEducation>
          <RecordAction>'.$PolicyDetails['InsuredDetail']['RecordAction'].'</RecordAction>
        </InsuredDetail>
        <BankingDetail>
          <BranchName>'.$PolicyDetails['BankingDetail']['BranchName'].'</BranchName>
          <BankId>'.$PolicyDetails['BankingDetail']['BranchID'].'</BankId>
          <DebitDay>'.$PolicyDetails['BankingDetail']['DebitDay'].'</DebitDay>
        </BankingDetail>
        <Pets>';
        foreach ($PolicyDetails['Pets'] as $pet) {
        	$SOAPBody .= '<PetDetail>
            <InceptionDate>'.$pet['InceptionDate'].'</InceptionDate>
            <PlanCode>'.$pet['PlanCode'].'</PlanCode>
            <TypeCode>'.$pet['TypeCode'].'</TypeCode>
            <BreedCode>'.$pet['BreedCode'].'</BreedCode>
            <Gender>'.$pet['Gender'].'</Gender>
            <Name>'.$pet['Name'].'</Name>
            <PureBred>'.$pet['PureBred'].'</PureBred>
            <Microchip>'.$pet['Microchip'].'</Microchip>
            <PreCondition>'.$pet['PreCondition'].'</PreCondition>
            <DOB>'.$pet['DOB'].'</DOB>
            <Neutered>'.$pet['Neutered'].'</Neutered>
            <Vaccinations>'.$pet['Vaccinations'].'</Vaccinations>
            <OtherDesc>'.$pet['OtherDesc'].'</OtherDesc>
            <ExcessPercentage>'.$pet['ExcessPercentage'].'</ExcessPercentage>
            <ExcessOption>'.$pet['ExcessOption'].'</ExcessOption>
            <PremiumDetail>
              <RiskPremium>'.$pet['PremiumDetail']['RiskPremium'].'</RiskPremium>
              <ProrataAmount>'.$pet['PremiumDetail']['ProrataAmount'].'</ProrataAmount>
              <GST>'.$pet['PremiumDetail']['GST'].'</GST>
              <ProrataGST>'.$pet['PremiumDetail']['ProrataGST'].'</ProrataGST>
            </PremiumDetail>
            <BenefitID>'.$pet['BenefitID'].'</BenefitID>
            <Extensions xsi:nil="true" />
            <NYPInd>'.$pet['NYPInd'].'</NYPInd>
            <RecordAction>'.$pet['RecordAction'].'</RecordAction>
            <Status>'.$pet['Status'].'</Status>
          </PetDetail>';
        }
    $SOAPBody .= '</Pets>
        <CampaignId>'.$PolicyDetails['CampaignId'].'</CampaignId>
        <SaleType>'.$PolicyDetails['SaleType'].'</SaleType>
        <PaymentFrequency>'.$PolicyDetails['PaymentFrequency'].'</PaymentFrequency>
        <PaymentTerm>'.$PolicyDetails['PaymentTerm'].'</PaymentTerm>
        <PaymentMethod>'.$PolicyDetails['PaymentMethod'].'</PaymentMethod>
      </PolicyDetails>
      <ExtReference>'.$ExtReference.'</ExtReference>
    </inceptPolicy>
      </soap:Body>
</soap:Envelope>';
    if (sizeof(errorHandler::policy($PolicyDetails)) <= 0) {
      $this->SOAPBody = $SOAPBody;
      $this->SOAPxml = $this->SOAPHeader.$this->SOAPBody;
      $this->SOAPResponseArray = $this->_send()['inceptPolicyResponse']['inceptPolicyResult'];
  	  $this->SOAPstatus = $this->SOAPResponseArray['Status'];
    } else {
      $this->SOAPstatus = 'Failure - Please check ErrorArray';
      $this->ErrorArray = errorHandler::policy($PolicyDetails);
    }

	}

	// multirater section 
	function GetProductQuote(array $quote, string $SchemeCode) {
		$this->url = $this->isTest ? 'http://apps.softsure.co.za/Multirates_UAT/Service.asmx?WSDL' : 'http://apps.softsure.co.za/Multirates/Service.asmx?WSDL';
		$SOAPBody = '
<soap:Body>
		<GetProductQuote xmlns="http://www.softsure.co.za/">
  <QuoteData>
    <Insured>
      <Surname>'.$quote['Insured']['Surname'].'</Surname>
      <FirstName>'.$quote['Insured']['FirstName'].'</FirstName>
      <Initials>'.$quote['Insured']['Initials'].'</Initials>
      <IDType>'.$quote['Insured']['IDType'].'</IDType>
      <IDNumber>'.$quote['Insured']['IDNumber'].'</IDNumber>
      <GenderCde>'.$quote['Insured']['GenderCde'].'</GenderCde>
      <MaritalCde>'.$quote['Insured']['MaritalCde'].'</MaritalCde>
      <DateOfBirth>'.$quote['Insured']['DateOfBirth'].'</DateOfBirth>
      <AddressLine1>'.$quote['Insured']['AddressLine1'].'</AddressLine1>
      <Suburb>'.$quote['Insured']['Suburb'].'</Suburb>
      <Town>'.$quote['Insured']['Town'].'</Town>
      <Postcode>'.$quote['Insured']['Postcode'].'</Postcode>
      <ContactNo>'.$quote['Insured']['ContactNo'].'</ContactNo>
      <CustomerType>'.$quote['Insured']['CustomerType'].'</CustomerType>
      <UniqueReference>'.$quote['Insured']['UniqueReference'].'</UniqueReference>
      <Language>'.$quote['Insured']['Language'].'</Language>
      <Title>'.$quote['Insured']['Title'].'</Title>
      <MobileNo>'.$quote['Insured']['MobileNo'].'</MobileNo>
      <EmailAddress>'.$quote['Insured']['EmailAddress'].'</EmailAddress>
      <HighestEducation>'.$quote['Insured']['HighestEducation'].'</HighestEducation>
      <RecordAction>'.$quote['Insured']['RecordAction'].'</RecordAction>
      <OccupationCde>'.$quote['Insured']['OccupationCde'].'</OccupationCde>
    </Insured>
    <Pet>
		<ItemSeqNo>'.$quote['Pet']['ItemSeqNo'].'</ItemSeqNo>
		<PlanCode>'.$quote['Pet']['PlanCode'].'</PlanCode>
		<ExcessBuster>'.$quote['Pet']['ExcessBuster'].'</ExcessBuster>
		<PetAge>'.$quote['Pet']['PetAge'].'</PetAge>
		<PaymentFrequency>'.$quote['Pet']['PaymentFrequency'].'</PaymentFrequency>
	</Pet>
    <BrokerCode>'.$quote['BrokerCode'].'</BrokerCode>
    <SubBrokerCode>'.$quote['SubBrokerCode'].'</SubBrokerCode>
    <AgentCode>'.$quote['AgentCode'].'</AgentCode>
    <CampaignID>'.$quote['CampaignID'].'</CampaignID>
  </QuoteData>
  <SchemeCode>'.$SchemeCode.'</SchemeCode>
</GetProductQuote>
      </soap:Body>
</soap:Envelope>';
    if (sizeof(errorHandler::quote($quote)) <= 0) {
      $this->SOAPBody = $SOAPBody;
  		$this->SOAPxml = $this->SOAPHeader.$this->SOAPBody;
  		$this->SOAPResponseArray = $this->_send(); 
  		$this->SOAPstatus = $this->SOAPResponseArray['Status']; 
    } else {
      $this->SOAPstatus = 'Failure - Please check ErrorArray';
      $this->ErrorArray = errorHandler::quote($quote);
    }
	}
}
