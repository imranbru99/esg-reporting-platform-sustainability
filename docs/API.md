# REST API & Integration Specification

The ESG Reporting Platform provides a REST API authenticated via Laravel Sanctum bearer tokens. Machine-to-machine integrations (IoT sub-meters, ERP systems, supplier portals) can ingest data and retrieve verified disclosures programmatically.

---

## 1. Authentication

All API requests must include a Bearer token in the `Authorization` header:

```http
Authorization: Bearer 1|qwertysanctumtokenexample12345
Accept: application/json
Content-Type: application/json
```

Generate tokens via the **Integrations Hub** Filament screen or using the demo API user:
- Email: `api@integration.test`
- Password: `password`

---

## 2. API Endpoints

### Data Collection & Ingestion

#### Ingest Metric Data Point
```http
POST /api/v1/metrics/entries
```

**Request Payload:**
```json
{
  "metric_code": "GHG-S1",
  "branch_code": "APEX-GAZ",
  "period_start": "2024-01-01",
  "period_end": "2024-01-31",
  "activity_value": 245.50,
  "activity_unit": "L",
  "emission_factor_code": "DEFRA-DSL-2024",
  "notes": "Automated diesel generator telemetry ingestion."
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "data": {
    "entry_id": 1042,
    "metric_code": "GHG-S1",
    "normalized_value": 245.50,
    "calculated_co2e": 0.6579,
    "status": "submitted"
  }
}
```

---

### IoT Smart Meter Telemetry

#### Submit Meter Interval Reading
```http
POST /api/v1/iot/readings
```

**Request Payload:**
```json
{
  "meter_serial": "MTR-ELEC-GAZ-01",
  "reading_timestamp": "2024-11-07T14:30:00Z",
  "cumulative_kwh": 184520.40,
  "interval_kwh": 38.20,
  "power_factor": 0.94
}
```

**Response (200 OK):**
```json
{
  "status": "ingested",
  "meter": "MTR-ELEC-GAZ-01",
  "calculated_scope2_co2e": 0.0225
}
```

---

### Reports & Disclosure Data Books

#### Get Verified Disclosures (JSON Data Book)
```http
GET /api/v1/disclosures?year=2024&framework=gri
```

**Response (200 OK):**
```json
{
  "reporting_period": "2024",
  "framework": "GRI Universal Standards",
  "disclosures": [
    {
      "code": "GRI 305-1",
      "title": "Direct (Scope 1) GHG emissions",
      "value": 2450.80,
      "unit": "tCO2e",
      "status": "verified",
      "evidence_hash": "e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855"
    },
    {
      "code": "GRI 305-2",
      "title": "Energy indirect (Scope 2) GHG emissions",
      "value": 1840.25,
      "unit": "tCO2e",
      "status": "verified"
    }
  ]
}
```

---

## 3. Rate Limits & Multi-Tenancy

- Default rate limit: 120 requests/minute per organization API key.
- Webhooks can be configured to dispatch when reports are locked, auditor workpapers are signed off, or emissions exceed anomaly thresholds.

---

## Author & Developer Information

- **Developer**: Imran Ahmed
- **Email**: me@imrandev.bd
- **Official Website**: [https://imrandev.bd](https://imrandev.bd)

