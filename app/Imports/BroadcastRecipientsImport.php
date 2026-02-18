<?php

namespace App\Imports;

class BroadcastRecipientsImport
{
    /**
     * @return array{phone: string, name: ?string, message: ?string}[]
     */
    public function toArray(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return [];
        }
        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);

            return [];
        }
        $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);
        if (strpos($firstLine, "\t") !== false) {
            $delimiter = "\t";
        } elseif (strpos($firstLine, ';') !== false && substr_count($firstLine, ';') >= substr_count($firstLine, ',')) {
            $delimiter = ';';
        } else {
            $delimiter = ',';
        }
        $header = str_getcsv(trim($firstLine), $delimiter);
        $header = array_map(fn ($c) => preg_replace('/^\xEF\xBB\xBF/', '', trim((string) $c)), $header);
        $phoneIndex = $this->findColumnIndex($header, ['phone', 'nomor', 'no', 'number']);
        if ($phoneIndex === null && isset($header[0])) {
            $phoneIndex = 0;
        }
        $nameIndex = $this->findColumnIndex($header, ['name', 'nama']);
        $messageIndex = $this->findColumnIndex($header, ['pesan', 'message', 'msg']);

        while (($line = fgets($handle)) !== false) {
            $data = str_getcsv(trim($line), $delimiter);
            $phoneRaw = $phoneIndex !== null && isset($data[$phoneIndex]) ? trim((string) $data[$phoneIndex]) : '';
            $phone = $this->normalizePhoneFromCell($phoneRaw);
            $name = $nameIndex !== null && isset($data[$nameIndex]) ? trim((string) $data[$nameIndex]) : null;
            $message = $messageIndex !== null && isset($data[$messageIndex]) ? trim((string) $data[$messageIndex]) : null;
            if ($phone !== '') {
                $rows[] = [
                    'phone' => $phone,
                    'name' => $name ?: null,
                    'message' => $message !== '' ? $message : null,
                ];
            }
        }
        fclose($handle);

        return $rows;
    }

    /**
     * @param  array<string>  $header
     * @param  array<string>  $names
     */
    protected function findColumnIndex(array $header, array $names): ?int
    {
        $header = array_map(function ($c) {
            $c = preg_replace('/^\xEF\xBB\xBF/', '', trim((string) $c));

            return strtolower($c);
        }, $header);
        foreach ($names as $name) {
            $i = array_search(strtolower($name), $header, true);
            if ($i !== false) {
                return $i;
            }
        }

        return null;
    }

    /**
     * Normalisasi nilai sel: ubah notasi ilmiah Excel (6.28516E+12) jadi angka penuh
     * dengan string agar digit tidak jadi nol (presisi penuh).
     */
    protected function normalizePhoneFromCell(string $value): string
    {
        if ($value === '') {
            return '';
        }
        $value = str_replace(',', '.', $value);
        if (preg_match('/^(\d+\.?\d*)E([+-]?\d+)$/i', trim($value), $m)) {
            $coef = $m[1];
            $exp = (int) $m[2];
            $dotPos = strpos($coef, '.');
            $digitsBeforeDot = $dotPos !== false ? $dotPos : strlen($coef);
            $coefDigits = str_replace('.', '', $coef);
            $resultLen = $digitsBeforeDot + $exp;
            if ($resultLen <= 0) {
                return '0';
            }
            if (strlen($coefDigits) >= $resultLen) {
                return substr($coefDigits, 0, $resultLen);
            }

            return $coefDigits.str_repeat('0', $resultLen - strlen($coefDigits));
        }

        return preg_replace('/\D/', '', $value);
    }
}
