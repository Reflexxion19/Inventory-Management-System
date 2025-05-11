#pragma once
#include <freertos/FreeRTOS.h>
#include <freertos/task.h>
#include "driver/ledc.h"

void servo_init();
void servoRotate(int angle);
void storage_open(void *pvParameters);