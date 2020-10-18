<?php


namespace Api\Common\OpenApi\Builders;


use cebe\openapi\spec\Contact;

class ContactsBuilder
{

    public function generateContact(): Contact
    {
        $configContacts = config('openapi.contacs', []);

        return new Contact($configContacts);
    }
}
