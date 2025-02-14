<?php

namespace App\Application\Advertisement\Model;

enum AdvertisementStatusEnum: int
{
    case EDITED    = 1; # после создания или редактирования или неудачной модерации
    case MODERATED = 2; # отправили на модерацию
    case VERIFIED  = 3; # после модерации или после снятия с публикации
    case PENDING   = 4; # отправили на публикацию - промежуточное состояние
    case PUBLISHED = 5; # публикуется
    case ARCHIVED  = 6; # архивированные объявления - можно перевести из EDITED VERIFIED PUBLISHED

    public static function canChangeStatus(AdvertisementStatusEnum $newStatus, AdvertisementModelInterface $model): bool
    {
        $map = [
            self::EDITED->value => [
                self::EDITED,
                self::VERIFIED,
                self::PUBLISHED,
                self::ARCHIVED,
                self::MODERATED,
            ],
            self::MODERATED->value => [
                self::EDITED,
            ],
            self::VERIFIED->value => [
                self::MODERATED,
                self::PUBLISHED,
            ],
            self::PENDING->value => [
                self::VERIFIED,
            ],
            self::PUBLISHED->value => [
                self::VERIFIED,
            ],
            self::ARCHIVED->value => [
                self::EDITED,
                self::VERIFIED,
                self::PUBLISHED,
            ]
        ];

        return in_array($model->getStatus(), $map[$newStatus->value], true);
    }

    public function getFullName(): string
    {
        return match ($this) {
            self::EDITED => 'Редактируется',
            self::MODERATED => 'Модерируется',
            self::VERIFIED => 'Успешно проверено',
            self::PENDING => 'Оплата',
            self::PUBLISHED => 'Опубликовано',
            self::ARCHIVED => 'В архиве',
        };
    }
}
