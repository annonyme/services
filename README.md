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

## Usage in PHP

```
$cont = Container::instance();
$cont->addServiceDescriptor(json_decode(file_get_content('services.json'), true));
$outer = $cont->get('testservice/outer');
```
