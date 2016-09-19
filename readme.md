FORMAT: 1A

# STATUS_SERVICE

# Statuses [/status]
Status resource representation.

## Show all status [GET /status]


+ Request (application/json)
    + Body

            {
                "search": {
                    "id": "string",
                    "reference": "string",
                    "tail": "boolean",
                    "head": "boolean",
                    "status": "string"
                },
                "sort": {
                    "newest": "asc|desc",
                    "position": "desc|asc",
                    "reference": "desc|asc"
                },
                "take": "integer",
                "skip": "integer"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "data": {
                        "id": "string",
                        "ref_id": "string",
                        "next_id": "string",
                        "status": "string",
                        "0": "position",
                        "1": "string"
                    },
                    "count": "integer"
                }
            }

## Store Status [POST /status]


+ Request (application/json)
    + Body

            {
                "id": "string",
                "ref_id": "string",
                "next_id": "string",
                "status": "string",
                "0": "position",
                "1": "string"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "id": "string",
                    "ref_id": "string",
                    "next_id": "string",
                    "status": "string",
                    "0": "position",
                    "1": "string"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }

## Delete Status [DELETE /status]


+ Request (application/json)
    + Body

            {
                "id": null
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "id": "string",
                    "ref_id": "string",
                    "next_id": "string",
                    "status": "string",
                    "0": "position",
                    "1": "string"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }