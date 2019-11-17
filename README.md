# hannespries/services

Simple and easy to use PHP service-container with dependency injection.

## Service Description Format
```
{
    "testservice/outer": {
        "class": "test\Service",
        "arguments": [
            {"id": "test", "type": "primitive"},
            {"id": "testservice/inner", "type": "service"}
        ]
    },
    "testservice/inner": {
        "class": "test\InnerService"
    }
}
```
