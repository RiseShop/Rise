# Geo Bundle

SQL schema and dump available in `Resources/sql`

```php
class GeoListener
{
    protected $geo;

    public function __construct(Geo $geo)
    {
        $this->geo = $geo;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        if ($this->geo->detect($request->getClientIp())) {
            // do something
        }
    }
}
```