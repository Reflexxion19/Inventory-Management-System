#pragma once
#include <string.h>
#include <nvs_flash.h>
#include <nvs.h>
#include <esp_event.h>
#include <esp_log.h>
#include <sodium.h>
char *decrypt_encrypt(const char *input);
void init_sodium();