# E-Invoice API cURL Reference

Base URL used in the local code:

```bash
BASE_URL="https://sandb-api.mastersindia.co/api/v1"
```

For production, replace `BASE_URL` with the configured `EINVOICE_API_URL`.

## 1. Get JWT Token

```bash
curl --location "$BASE_URL/token-auth" \
  --header "Accept: application/json" \
  --form "username=YOUR_API_USERNAME_OR_GSTIN" \
  --form "password=YOUR_API_PASSWORD"
```

Use the returned `token` value in the `Authorization` header:

```bash
AUTH_HEADER="Authorization: JWT YOUR_TOKEN"
```

## 2. Get E-Invoice Details By IRN

Endpoint:

```text
GET /get-einvoice?gstin={gstin}&irn={irn}
```

cURL:

```bash
curl --location "$BASE_URL/get-einvoice?gstin=27AAGCI3319H1ZM&irn=YOUR_IRN" \
  --header "$AUTH_HEADER" \
  --header "Accept: application/json"
```

Notes from the API docs:

- `gstin` is the GSTIN used for the API/e-invoice account.
- `irn` is the generated IRN.
- IRN details can be retrieved only within the API provider's allowed retrieval window.

## 3. Get E-Invoice Details By Document Details

Endpoint:

```text
GET /get-einvoice-bydoc?user_gstin={user_gstin}&document_type={document_type}&document_number={document_number}&document_date={document_date}
```

cURL:

```bash
curl --location "$BASE_URL/get-einvoice-bydoc?user_gstin=27AAGCI3319H1ZM&document_type=INV&document_number=INV-001&document_date=08/05/2026" \
  --header "$AUTH_HEADER" \
  --header "Accept: application/json"
```

## 4. Cancel E-Invoice / IRN

Endpoint:

```text
POST /cancel-einvoice/
```

cURL:

```bash
curl --location "$BASE_URL/cancel-einvoice/" \
  --header "$AUTH_HEADER" \
  --header "Content-Type: application/json" \
  --header "Accept: application/json" \
  --data '{
    "user_gstin": "27AAGCI3319H1ZM",
    "irn": "YOUR_IRN",
    "cancel_reason": "2",
    "cancel_remarks": "Data entry mistake"
  }'
```

Cancel reason values used in the application UI:

```text
1 = Duplicate IRN
2 = Data entry mistake
3 = Order cancelled
4 = Others
```

Cancellation notes from the API docs:

- IRN can be cancelled only within 24 hours of IRN generation.
- IRN cannot be cancelled if a valid/active E-Way Bill exists for the same IRN.
- Cancellation can be done by active or suspended taxpayers.
