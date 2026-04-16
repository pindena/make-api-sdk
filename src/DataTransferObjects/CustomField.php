<?php

namespace Pindena\MakeSDK\DataTransferObjects;

class CustomField
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $label = null,
        public readonly ?string $value = null,
        public readonly ?array $choices = null,
        public readonly ?string $datatype = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            label: $data['label'] ?? null,
            value: $data['value'] ?? null,
            choices: $data['choices'] ?? null,
            datatype: $data['datatype'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'label' => $this->label,
            'value' => $this->value,
            'choices' => $this->choices,
            'datatype' => $this->datatype,
        ], fn ($value) => $value !== null);
    }
}
