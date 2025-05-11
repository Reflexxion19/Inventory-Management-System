#include <esp_event.h>
#include <esp_log.h>
#include <nvs_flash.h>
#include "esp_sntp.h"
#include "rfid.c"

void app_main(void)
{
    nvs_flash_init();

    xTaskCreate(rfidReaderTask, "rfid_reader", 4096, NULL, 5, NULL);

    newData("cvBxX9uHKuuQjhNAOfpN2Hix8Lt20TMunhmT1Kep1B3ApBvVkmhaOChuA6ILu09tyo/gPQ/5h9Ir0aREZXyH4Ar1KnKUAA==");
}