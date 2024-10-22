<?php

declare(strict_types=1);

namespace Toobo;

use Wikimedia\Bcp47Code\Bcp47Code;

readonly class Bcp47Tag implements Bcp47Code, \Stringable, \JsonSerializable
{
    protected bool $isRtl;

    /**
     * @param string $tag
     * @return static
     */
    public static function new(string $tag): static
    {
        $data = Bcp47::splitTag($tag);
        if (array_filter($data) === []) {
            throw new \LogicException("{$tag} is not a valid BCP 47 tag.");
        }

        return new static(array_filter($data));
    }

    /**
     * @param array<string, non-empty-string> $data
     */
    final protected function __construct(
        protected array $data
    ) {
    }

    /**
     * @return string
     */
    public function toBcp47Code(): string
    {
        return implode('-', $this->data);
    }

    /**
     * @param Bcp47Code $other
     * @return bool
     */
    public function isSameCodeAs(Bcp47Code $other): bool
    {
        if ($other instanceof static) {
            return $this->toBcp47Code() === $other->toBcp47Code();
        }

        return static::new($other->toBcp47Code())->isSameCodeAs($this);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toBcp47Code();
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return isset($this->data[Bcp47::GRANDFATHERED]);
    }

    /**
     * @return non-empty-string
     */
    public function language(): string
    {
        if ($this->isDeprecated()) {
            return match ($this->data[Bcp47::GRANDFATHERED] ?? null) {
                'i-enochian' => 'enochian',
                'i-mingo' => 'mingo',
                'cel-gaulish' => 'gaulish',
                'zh-min' => 'zh',
                default => 'und',
            };
        }

        return $this->data[Bcp47::LANGUAGE] ?? 'und';
    }

    /**
     * @return non-empty-string|null
     */
    public function extLang(): ?string
    {
        return $this->data[Bcp47::LANGUAGE_EXTENSION] ?? null;
    }

    /**
     * @return non-empty-string|null
     */
    public function script(): ?string
    {
        return $this->data[Bcp47::SCRIPT] ?? null;
    }

    /**
     * @return non-empty-string|null
     */
    public function region(): ?string
    {
        return $this->data[Bcp47::REGION] ?? null;
    }

    /**
     * @return non-empty-string|null
     */
    public function variant(): ?string
    {
        return $this->data[Bcp47::VARIANT] ?? null;
    }

    /**
     * @return non-empty-string|null
     */
    public function extension(): ?string
    {
        return $this->data[Bcp47::EXTENSION] ?? null;
    }

    /**
     * @return non-empty-string|null
     */
    public function privateUse(): ?string
    {
        return $this->data[Bcp47::PRIVATE_USE] ?? null;
    }

    /**
     * @return bool
     */
    public function isRtl(): bool
    {
        if (!isset($this->isRtl)) {
            /** @psalm-suppress InaccessibleProperty */
            $this->isRtl = Bcp47::isRtl($this->toBcp47Code());
        }

        return $this->isRtl;
    }
}
