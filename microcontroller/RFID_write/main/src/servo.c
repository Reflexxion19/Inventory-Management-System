#include "headers/servo.h"

void servo_init() {
    ledc_timer_config_t timer_cfg = {
        .speed_mode = LEDC_LOW_SPEED_MODE,
        .duty_resolution = LEDC_TIMER_13_BIT,
        .timer_num = LEDC_TIMER_0,
        .freq_hz = 50,
        .clk_cfg = LEDC_AUTO_CLK,
    };
    ledc_channel_config_t channel_cfg = {
        .gpio_num = 37,
        .speed_mode = LEDC_LOW_SPEED_MODE,
        .channel = LEDC_CHANNEL_0,
        .timer_sel = LEDC_TIMER_0,
        .duty = 0,
        .hpoint = 0,
    };
    ledc_timer_config(&timer_cfg);
    ledc_channel_config(&channel_cfg);
}

void servoRotate(int angle) {
    uint32_t duty = duty = (500 + angle * (2500 - 500) / 180) * 8191 / 20000;
    ledc_set_duty(LEDC_LOW_SPEED_MODE, LEDC_CHANNEL_0, duty);
    ledc_update_duty(LEDC_LOW_SPEED_MODE, LEDC_CHANNEL_0);
}

void storage_open(void *pvParameters){
    servoRotate(90);
    vTaskDelay(5000 / portTICK_PERIOD_MS);
    servoRotate(0);

    vTaskDelete(NULL);
}