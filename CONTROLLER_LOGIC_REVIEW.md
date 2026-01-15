# Controller Business Logic Review

## Summary
Review of all controllers to identify business logic that should be moved to services.

---

## Controllers Analysis

### 1. HomeController ✔ OK
**Status:** No refactoring needed

- Only sets page title and loads view
- Pure presentation layer

---

### 2. HelloController ✔ OK
**Status:** No refactoring needed

- Simple demo controller
- Direct echo output (not a business logic issue)

---

### 3. MinifigureController ✔ OK
**Status:** No refactoring needed

- `home()` — loads view only
- `index()` — delegates to service, loads view
- `detail()` — delegates to service, checks 404, loads view
- All logic correctly delegated to service

---

### 4. CartController ⚠ NEEDS REFACTOR
**Status:** Business logic in controller (cart rebuilding)

**Issue 1: Cart item rebuilding (lines 20-39 in `index()` method)**
- **Problem:** Controller calculates `lineTotal` and `totalCents` by looping cart items
- **Why it belongs in service:** 
  - Calculation of line totals is business logic
  - Cart data transformation is coordination between cart state and product data
  - Should be reusable if other parts of app need cart totals
- **Suggested fix:** Create `CartService::getCartWithTotals(array $sessionCart): array` 
  - Takes session cart, returns enriched cart items with totals
  - Example: `$cartData = $this->cartService->getCartWithTotals($_SESSION['cart']);`

**Issue 2: Cart manipulation logic (lines 45-87 in `add()`, `remove()`, `update()` methods)**
- **Problem:** Controller directly manipulates `$_SESSION['cart']` with increment/decrement logic
- **Why it belongs in service:**
  - Cart state management is business logic
  - Validation of quantity bounds (0 or negative = remove) is a business rule
  - Makes cart logic testable and reusable
- **Suggested fix:** Create `CartService` methods:
  - `CartService::addToCart(int $productId): void` — increments item
  - `CartService::removeFromCart(int $productId): void` — removes item
  - `CartService::updateQuantity(int $productId, int $quantity): void` — validates qty, then updates or removes

---

### 5. CheckoutController ⚠ NEEDS REFACTOR
**Status:** Heavy business logic in controller (cart rebuilding, order creation coordination)

**Issue 1: Cart rebuilding in `index()` method (lines 25-47)**
- **Problem:** Same cart rebuilding logic as CartController (lines 33-48 duplicate code)
- **Why it belongs in service:** Duplicate logic that should be centralized
- **Suggested fix:** Reuse `CartService::getCartWithTotals()`

**Issue 2: Cart rebuilding in `placeOrder()` method (lines 85-102)**
- **Problem:** Duplicate cart rebuilding and total calculation, then builds `$items` array for order
- **Why it belongs in service:** Complex order creation logic and data preparation
- **Suggested fix:** Create `OrderService::createOrderFromCart(string $name, string $email): int`
  - Takes form input, rebuilds cart internally, creates order
  - Handles: validation, cart→order item conversion, total calculation, cart clearing, returns orderId
  - Controller just: validates login, passes POST data, redirects

**Issue 3: Email validation (lines 76-82)**
- **Problem:** Checks if email and name are empty
- **Why it belongs in service:** Input validation is business logic
- **Suggested fix:** `OrderService::validateOrderInput(string $name, string $email): array|null`
  - Returns error array if invalid, null if valid
  - Controller uses it: if ($error = $service->validateOrderInput(...)) { show error }

**Current flow (bad):**
```
Controller: check login, get cart, validate input, rebuild cart, create order, clear cart, redirect
```

**Recommended flow (good):**
```
Controller: check login, validate input via service, create order via service, redirect
Service: validate, rebuild cart, create order, clear cart (all in one method)
```

---

### 6. AuthorizationController ⚠ NEEDS REFACTOR
**Status:** Authentication/validation logic in controller

**Issue 1: Registration validation (lines 26-39 in `register()` method)**
- **Problem:** 
  - Lines 26-31: Validates empty fields
  - Lines 33-38: Validates password match
  - Lines 40-45: Checks duplicate email
- **Why it belongs in service:** Input validation and business rules
- **Suggested fix:** Create `AuthService::validateRegistration(string $email, string $password, string $password2, string $name): array|null`
  - Returns error message if invalid, null if valid
  - Encapsulates all registration rules

**Issue 2: Password hashing and user creation (lines 47-48 in `register()` method)**
- **Problem:** `password_hash()` call in controller
- **Why it belongs in service:** Security/encryption logic is business logic
- **Suggested fix:** `AuthService::registerUser(string $email, string $password, string $name): int`
  - Handles: validation, hashing, creation, returns userId
  - Controller just receives userId

**Issue 3: Login validation and password verification (lines 77-106 in `login()` method)**
- **Problem:** 
  - Email/password presence check
  - Database lookup
  - `password_verify()` call
- **Why it belongs in service:** Authentication logic
- **Suggested fix:** `AuthService::authenticate(string $email, string $password): array|null`
  - Returns user data if credentials valid, null if invalid
  - Controller: if ($user = $service->authenticate(...)) { $_SESSION['user'] = ... }

**Current flow (bad):**
```
Controller: validate input, check duplicate, hash password, create user, set session
```

**Recommended flow (good):**
```
Controller: call register method, set session, redirect
Service: validate, check duplicate, hash password, create user, return userId
```

---

### 7. AdminController ✔ OK
**Status:** No refactoring needed

- Only loads dashboard view
- Authorization helper handles access control

---

### 8. AdminMinifigureController ✔ OK
**Status:** No refactoring needed

- All CRUD operations delegated to service
- Form input → service → redirect
- No calculation or complex logic in controller

---

### 9. AdminOrderController ✔ OK
**Status:** No refactoring needed

- All operations delegated to repository
- Simple form input → update/delete → redirect
- No business logic in controller

---

### 10. AdminUserController ✔ OK
**Status:** No refactoring needed

- All operations delegated to repository
- Simple form input → update → redirect
- No business logic in controller

---

### 11. UserOrderController ✔ OK
**Status:** No refactoring needed

- Only fetches user orders and loads view
- All logic in repository
- Authorization handled by helper

---

### 12. Api/MinifigureApiController ⚠ MINOR REFACTOR (Optional)
**Status:** Minor presentation logic in controller

**Issue: JSON serialization (lines 18-27)**
- **Problem:** Controller builds result array with data transformation (`priceEuro()`, string replace on imageUrl)
- **Severity:** LOW (not critical, but could be cleaner)
- **Why it could go to service:** API response formatting is separation of concerns
- **Suggested fix (Optional):** Create `MinifigureService::getMinifiguresForApi(): array`
  - Returns array formatted for JSON response
  - Controller just: `echo json_encode($this->service->getMinifiguresForApi())`
- **Note:** Only do this if you have multiple API endpoints; single endpoint is fine as-is

---

## Summary Table

| Controller | Status | Issues | Priority |
|-----------|--------|--------|----------|
| HomeController | ✔ OK | None | — |
| HelloController | ✔ OK | None | — |
| MinifigureController | ✔ OK | None | — |
| CartController | ⚠ Refactor | Cart rebuilding, cart manipulation | HIGH |
| CheckoutController | ⚠ Refactor | Duplicate cart logic, order creation | HIGH |
| AuthorizationController | ⚠ Refactor | Validation, hashing, auth logic | HIGH |
| AdminController | ✔ OK | None | — |
| AdminMinifigureController | ✔ OK | None | — |
| AdminOrderController | ✔ OK | None | — |
| AdminUserController | ✔ OK | None | — |
| UserOrderController | ✔ OK | None | — |
| Api/MinifigureApiController | ⚠ Optional | JSON formatting (minor) | LOW |

---

## Recommended Refactoring Order

1. **Create `CartService`** (fixes CartController + CheckoutController duplicate logic)
   - `getCartWithTotals(array $sessionCart): array`
   - `addToCart(int $productId): void`
   - `removeFromCart(int $productId): void`
   - `updateQuantity(int $productId, int $quantity): void`

2. **Create `OrderService`** (fixes CheckoutController complexity)
   - `createOrderFromCart(string $name, string $email): int|array` (returns orderId or error)
   - Internally: validate, rebuild cart, create order, clear cart

3. **Create `AuthService`** (fixes AuthorizationController)
   - `validateRegistration(...): array|null`
   - `registerUser(...): int`
   - `authenticate(string $email, string $password): array|null`

4. **Optional: Update Api/MinifigureApiController** (only if planning multiple API endpoints)
   - Add `getMinifiguresForApi()` to MinifigureService

---

## What NOT to Change

✔ Repositories — Correct level of abstraction  
✔ Helpers (Authorization) — Appropriate for access control checks  
✔ Direct HTTP operations (header, $_SESSION) in controllers — Acceptable  
✔ View loading in controllers — Correct responsibility  
