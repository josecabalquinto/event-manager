// SPDX-License-Identifier: MIT
pragma solidity ^0.8.19;

contract CertificateRegistry {
    struct Certificate {
        string participantName;
        string eventTitle;
        string eventDate;
        string completionDate;
        uint256 issuedAt;
        bool isValid;
    }

    mapping(string => Certificate) public certificates;
    
    event CertificateIssued(
        string indexed certificateHash,
        string participantName,
        string eventTitle,
        string eventDate,
        string completionDate,
        uint256 issuedAt
    );

    address public owner;

    modifier onlyOwner() {
        require(msg.sender == owner, "Only owner can perform this action");
        _;
    }

    constructor() {
        owner = msg.sender;
    }

    function issueCertificate(
        string memory certificateHash,
        string memory participantName,
        string memory eventTitle,
        string memory eventDate,
        string memory completionDate
    ) public onlyOwner {
        require(bytes(certificateHash).length > 0, "Certificate hash cannot be empty");
        require(bytes(participantName).length > 0, "Participant name cannot be empty");
        require(bytes(eventTitle).length > 0, "Event title cannot be empty");
        require(!certificates[certificateHash].isValid, "Certificate already exists");

        certificates[certificateHash] = Certificate({
            participantName: participantName,
            eventTitle: eventTitle,
            eventDate: eventDate,
            completionDate: completionDate,
            issuedAt: block.timestamp,
            isValid: true
        });

        emit CertificateIssued(
            certificateHash,
            participantName,
            eventTitle,
            eventDate,
            completionDate,
            block.timestamp
        );
    }

    function verifyCertificate(string memory certificateHash) 
        public 
        view 
        returns (
            bool isValid,
            string memory participantName,
            string memory eventTitle,
            string memory eventDate,
            string memory completionDate,
            uint256 issuedAt
        ) 
    {
        Certificate memory cert = certificates[certificateHash];
        return (
            cert.isValid,
            cert.participantName,
            cert.eventTitle,
            cert.eventDate,
            cert.completionDate,
            cert.issuedAt
        );
    }

    function revokeCertificate(string memory certificateHash) public onlyOwner {
        require(certificates[certificateHash].isValid, "Certificate does not exist or already revoked");
        certificates[certificateHash].isValid = false;
    }

    function transferOwnership(address newOwner) public onlyOwner {
        require(newOwner != address(0), "New owner cannot be zero address");
        owner = newOwner;
    }

    function getTotalCertificatesCount() public view returns (uint256) {
        // This is a simplified implementation
        // In production, you might want to keep a counter
        return 0;
    }
}