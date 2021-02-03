# Amazon SES Domain Verification PoC

This project contains a CLI application that leverages the
Amazon AWS SDK to automate domain verification on SES.

## Try it
```shell
# Pull the code
git clone https://github.com/nhedger/amazon-ses-api-poc.git
cd amazon-ses-api-poc

# Install dependencies
composer install

# Run it
./bin/ses add \
  --region eu-east-1 \
  --key '<AMAZON_KEY>' \
  --secret '<AMAZON_SECRET_KEY>' \
  example.tld
```

It returns the list of records that should be added to the domain's zone.

**Example output:**
```text
DNS records to add to the zone of example.tld :
TXT  _amazonses.example.tld   eS/tvBIJvKxJt8RBtgrTAsQoaL4EZTXc8EpWxfSOjgk=
CNAME omsxmjbjbjubzc55dxsyj2vyg5lzer44._domainkey.example.tld   omsxmjbjbjubzc55dxsyj2vyg5lzer44.dkim.amazonses.com
CNAME q7jtt5jjj35jaq5zo25u74ipmmtetpat._domainkey.example.tld   q7jtt5jjj35jaq5zo25u74ipmmtetpat.dkim.amazonses.com
CNAME wmrftsm5jukpp3tltdlahqlqdrnap5ub._domainkey.example.tld   wmrftsm5jukpp3tltdlahqlqdrnap5ub.dkim.amazonses.com
```
